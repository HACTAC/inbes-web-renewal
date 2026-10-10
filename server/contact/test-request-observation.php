<?php
declare(strict_types=1);

// Offline actual-handler checks. Explicit PHP extension/session stubs avoid any
// SMTP, Turnstile, real session, credential or network access on this test host.
if (($argv[1] ?? '') === '--child') {
    $case = $argv[2];
    $root = $argv[3];
    $_SERVER = ['DOCUMENT_ROOT' => $root, 'REQUEST_METHOD' => $case === 'get' ? 'GET' : 'POST',
        'HTTP_ORIGIN' => 'https://inbes.jp', 'HTTP_SEC_FETCH_SITE' => 'same-origin'];
    if (in_array($case, ['origin', 'bootstrap_missing', 'bootstrap_unprivate', 'disabled', 'alternate', 'log_unavailable'], true)) {
        $_SERVER['HTTP_ORIGIN'] = 'https://untrusted.example.test';
    }
    if ($case === 'fetch_site') $_SERVER['HTTP_SEC_FETCH_SITE'] = 'cross-site';
    $_POST = ['token' => $case === 'csrf' ? 'invalid' : str_repeat('a', 64),
        'category' => '商品化の相談', 'privacy' => $case === 'attachment' ? 'on' : '', 'name' => 'mock', 'email' => 'private-input@example.test',
        'message' => 'private request must not be logged'];
    $_FILES = [];
    if ($case === 'attachment') $_FILES['attachment'] = ['error' => UPLOAD_ERR_PARTIAL];
    $_SESSION = ['contact_tokens' => [str_repeat('a', 64) => time() - ($case === 'csrf_expired' ? 601 : 0)]];
    putenv('INBES_FORM_CONFIG=' . $root . '/.inbes-private/config.php');
    $GLOBALS['observation_http_status'] = 200;
    register_shutdown_function(static function (): void {
        echo "\n" . json_encode(['status' => $GLOBALS['observation_http_status']], JSON_THROW_ON_ERROR);
    });
    // Minimal mbstring stand-ins are test-only. Production requires real mbstring.
    if (!function_exists('mb_check_encoding')) {
        function mb_check_encoding($value, $encoding): bool { return preg_match('//u', $value) === 1; }
        function mb_strlen($value, $encoding): int { return iconv_strlen($value, 'UTF-8'); }
    }
    $shim = <<<'PHP'
namespace InbesObservationTest;
use \Throwable;
use \RuntimeException;
function extension_loaded($extension) { return true; }
function header($header) {}
function http_response_code($status) { $GLOBALS['observation_http_status'] = $status; }
function session_name($value) {}
function session_cache_limiter($value) {}
function session_set_cookie_params($value) {}
function session_start() { return true; }
function session_write_close() {}
PHP;
    $source = file_get_contents(__DIR__ . '/send.php');
    $source = substr($source, strpos($source, "\n") + 1);
    $source = str_replace('declare(strict_types=1);', '', $source);
    $source = str_replace('__DIR__', var_export(__DIR__, true), $source);
    $source = str_replace('InbesContact\\', '\\InbesContact\\', $source);
    // Evaluate exact handler logic with isolated environment functions only.
    eval('declare(strict_types=1);' . $shim . "\n" . $source);
    exit(1);
}

$checks = 0;
function observationAssert(bool $condition, string $label): void
{
    global $checks;
    if (!$condition) throw new RuntimeException($label);
    $checks++;
}
function observationWrite(string $path, string $bytes, int $mode = 0600): void
{
    file_put_contents($path, $bytes); chmod($path, $mode);
}
function observationClean(string $path): void
{
    if (!is_dir($path)) { unlink($path); return; }
    foreach (scandir($path) as $name) {
        if ($name !== '.' && $name !== '..') observationClean($path . '/' . $name);
    }
    rmdir($path);
}
$base = sys_get_temp_dir() . '/inbes-observation-' . bin2hex(random_bytes(8));
mkdir($base, 0700);
$cases = ['wrapper', 'origin', 'fetch_site', 'csrf', 'csrf_expired', 'validation', 'attachment', 'get', 'bootstrap_missing', 'bootstrap_unprivate', 'disabled', 'alternate', 'log_unavailable'];
try {
    foreach ($cases as $case) {
        $root = $base . '/' . $case;
        mkdir($root, 0755); mkdir($root . '/.inbes-private', 0700);
        $private = $root . '/.inbes-private';
        observationWrite($private . '/.htaccess', file_get_contents(__DIR__ . '/private-template/.htaccess'));
        if ($case !== 'bootstrap_missing') {
            observationWrite($private . '/events.jsonl', $case === 'log_unavailable' ? str_repeat('x', 1048577) : '', $case === 'bootstrap_unprivate' ? 0644 : 0600);
        }
        $config = ['enabled' => true, 'origin' => 'https://inbes.jp', 'autoload' => '/mock-unused/autoload.php',
            'state_file' => '/mock-unused/rate.json', 'rate_key' => str_repeat('mock', 16),
            'smtp_host' => 'smtp.example.test', 'smtp_port' => 465, 'smtp_security' => 'implicit_tls',
            'smtp_user' => 'mock', 'smtp_password' => 'private-password-must-not-be-logged',
            'from_address' => 'mock@example.test', 'to_address' => 'mock@example.test', 'turnstile_enabled' => false];
        if (!in_array($case, ['bootstrap_missing', 'bootstrap_unprivate', 'disabled'], true)) {
            $config['event_file'] = $private . ($case === 'alternate' ? '/configured-events.jsonl' : '/events.jsonl');
        }
        if ($case === 'alternate') observationWrite($config['event_file'], '');
        observationWrite($private . '/config.php', $case === 'wrapper'
            ? "<?php throw new RuntimeException('private exception must not be logged');\n"
            : '<?php return ' . var_export($config, true) . ";\n");
        $process = proc_open([PHP_BINARY, __FILE__, '--child', $case, $root],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) throw new RuntimeException('test process unavailable');
        $output = stream_get_contents($pipes[1]); $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]); fclose($pipes[2]);
        observationAssert(proc_close($process) === 0 && $stderr === '', 'isolated handler ' . $case);
        [$bodyRaw, $statusRaw] = explode("\n", trim($output));
        $body = json_decode($bodyRaw, true, 8, JSON_THROW_ON_ERROR);
        $status = json_decode($statusRaw, true, 8, JSON_THROW_ON_ERROR)['status'];
        observationAssert($status === ($case === 'wrapper' ? 503 : (in_array($case, ['validation', 'attachment'], true) ? 422 : ($case === 'get' ? 200 : 403))), 'response status ' . $case);
        if ($case === 'get') {
            observationAssert($body['ok'] === true && $body['turnstileRequired'] === false && preg_match('/\A[a-f0-9]{64}\z/', $body['token']) === 1, 'GET response preserved');
        } else {
            observationAssert($body === ($case === 'wrapper' ? ['ok' => false, 'uncertain' => false] : ['ok' => false]), 'response body unchanged ' . $case);
        }
        $bootstrap = is_file($private . '/events.jsonl') ? file_get_contents($private . '/events.jsonl') : '';
        $configured = $case === 'alternate' ? file_get_contents($private . '/configured-events.jsonl') : $bootstrap;
        $rows = $case === 'log_unavailable' || $bootstrap === '' ? [] : array_map(static fn($line) => json_decode($line, true, 8, JSON_THROW_ON_ERROR), explode("\n", trim($bootstrap)));
        $events = array_column($rows, 'event');
        $expected = match ($case) {
            'wrapper' => ['request_received', 'service_failed'],
            'origin' => ['request_received', 'origin_rejected'],
            'fetch_site' => ['request_received', 'fetch_site_rejected'],
            'csrf', 'csrf_expired' => ['request_received', 'csrf_rejected'],
            'validation', 'attachment' => ['request_received', 'validation_rejected'],
            'disabled', 'alternate' => ['request_received'],
            default => [],
        };
        observationAssert($events === $expected, 'observed events ' . $case);
        foreach ($rows as $row) {
            observationAssert(array_keys($row) === ['time', 'event', 'stage', 'reference'] && $row['reference'] === null, 'minimal event fields ' . $case);
        }
        if ($case === 'wrapper') observationAssert(array_column($rows, 'stage') === ['configuration', 'configuration'], 'wrapper failure observed before config return');
        if ($case === 'alternate') {
            $alternate = json_decode(trim($configured), true, 8, JSON_THROW_ON_ERROR);
            observationAssert($alternate['event'] === 'origin_rejected', 'configured log target preserved');
        }
        foreach (['private exception', 'private request', 'private-password', 'private-input@', 'untrusted.example'] as $marker) {
            observationAssert(!str_contains($bootstrap . $configured . $output, $marker), 'private data absent ' . $case);
        }
    }
    echo json_encode(['passed' => true, 'cases' => count($cases), 'checks' => $checks, 'real_mail_sent' => false, 'network_calls' => 0,
        'runtime_extensions_and_sessions' => 'explicit test-only stubs']) . "\n";
} finally {
    observationClean($base);
}
