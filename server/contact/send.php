<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

require __DIR__ . '/FormRules.php';
require __DIR__ . '/EventLog.php';
require __DIR__ . '/Turnstile.php';
require __DIR__ . '/PrivateStorage.php';

ini_set('display_errors', '0');
ini_set('log_errors', '0');
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

function respond(int $status, array $body): never
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

function privateFile(string $path, string $documentRoot): string
{
    return InbesContact\privateStorageFile($path, $documentRoot);
}

function configuredMailer(array $config): PHPMailer
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $config['smtp_host'];
    $mail->Port = $config['smtp_port'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp_user'];
    $mail->Password = $config['smtp_password'];
    $mail->SMTPSecure = $config['smtp_security'] === 'implicit_tls' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPDebug = 0;
    $mail->Timeout = 20;
    $mail->SMTPOptions = ['ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'allow_self_signed' => false]];
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($config['from_address'], 'INBES');
    $mail->isHTML(false);
    return $mail;
}

$deliveryAttempted = false;
$eventPath = null;
$reference = null;
$stage = 'configuration';
try {
    foreach (['fileinfo', 'mbstring', 'openssl', 'zip'] as $extension) {
        if (!extension_loaded($extension)) {
            throw new RuntimeException('required extension unavailable');
        }
    }
    $root = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    // Observe configuration failures before the private wrapper can throw.
    // This fixed bootstrap path is optional: logging failure changes no response.
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && is_string($root)) {
        try {
            $eventPath = privateFile($root . '/.inbes-private/events.jsonl', $root);
        } catch (Throwable $error) {
            $eventPath = null;
        }
        InbesContact\recordEvent($eventPath, 'request_received', $stage, null);
    }
    $configPath = getenv('INBES_FORM_CONFIG');
    if ($configPath === false || $configPath === '') {
        $configPath = require __DIR__ . '/settings-path.php';
    }
    if (!is_string($root) || !is_string($configPath) || $configPath === '') {
        throw new RuntimeException('configuration unavailable');
    }
    $config = require privateFile($configPath, $root);
    if (!is_array($config) || ($config['enabled'] ?? false) !== true) {
        throw new RuntimeException('not enabled');
    }
    foreach (['origin', 'autoload', 'state_file', 'rate_key', 'smtp_host', 'smtp_user', 'smtp_password', 'from_address', 'to_address'] as $key) {
        if (!is_string($config[$key] ?? null) || $config[$key] === '') {
            throw new RuntimeException('configuration incomplete');
        }
    }
    $origin = parse_url($config['origin']);
    if (!$origin || ($origin['scheme'] ?? '') !== 'https' || empty($origin['host']) ||
        isset($origin['user']) || isset($origin['pass']) || isset($origin['query']) || isset($origin['fragment']) ||
        !in_array($origin['path'] ?? '', ['', '/'], true) || strlen($config['rate_key']) < 32 ||
        !preg_match('/\A[a-zA-Z0-9](?:[a-zA-Z0-9.-]*[a-zA-Z0-9])?\z/', $config['smtp_host']) ||
        !in_array($config['smtp_security'] ?? '', ['implicit_tls', 'starttls'], true) ||
        !is_int($config['smtp_port'] ?? null) || $config['smtp_port'] < 1 || $config['smtp_port'] > 65535 ||
        !filter_var($config['from_address'], FILTER_VALIDATE_EMAIL) || !filter_var($config['to_address'], FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('invalid configuration');
    }
    // Once configuration is available, preserve its selected/disabled log target.
    $eventPath = null;
    if (($config['event_file'] ?? '') !== '') {
        if (!is_string($config['event_file'])) {
            throw new RuntimeException('invalid event configuration');
        }
        $eventPath = privateFile($config['event_file'], $root);
    }
    $turnstileEnabled = $config['turnstile_enabled'] ?? false;
    if (!is_bool($turnstileEnabled) || ($turnstileEnabled &&
        (!is_string($config['turnstile_secret'] ?? null) || $config['turnstile_secret'] === '' ||
         in_array($config['turnstile_secret'], ['1x0000000000000000000000000000000AA', '2x0000000000000000000000000000000AA', '3x0000000000000000000000000000000AA'], true)))) {
        throw new RuntimeException('invalid verification configuration');
    }
    $stage = 'request';
    $method = $_SERVER['REQUEST_METHOD'] ?? '';
    if (!in_array($method, ['GET', 'POST'], true)) {
        header('Allow: GET, POST');
        respond(405, ['ok' => false]);
    }
    if ($method === 'POST' && ($_SERVER['HTTP_ORIGIN'] ?? '') !== rtrim($config['origin'], '/')) {
        InbesContact\recordEvent($eventPath, 'origin_rejected', $stage, null);
        respond(403, ['ok' => false]);
    }
    if (isset($_SERVER['HTTP_SEC_FETCH_SITE']) && !in_array($_SERVER['HTTP_SEC_FETCH_SITE'], ['same-origin', 'none'], true)) {
        if ($method === 'POST') {
            InbesContact\recordEvent($eventPath, 'fetch_site_rejected', $stage, null);
        }
        respond(403, ['ok' => false]);
    }
    session_name('inbes_contact');
    session_cache_limiter('');
    session_set_cookie_params(['lifetime' => 0, 'path' => '/contact/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
    ini_set('session.use_strict_mode', '1');
    if (!session_start()) {
        throw new RuntimeException('session unavailable');
    }
    if ($method === 'GET') {
        $tokens = $_SESSION['contact_tokens'] ?? [];
        if (!is_array($tokens)) {
            $tokens = [];
        }
        $tokens = array_filter($tokens, static fn($issued) => is_int($issued) && $issued >= time() - 600);
        $tokens = array_slice($tokens, -7, null, true);
        $token = bin2hex(random_bytes(32));
        $tokens[$token] = time();
        $_SESSION['contact_tokens'] = $tokens;
        session_write_close();
        respond(200, ['ok' => true, 'token' => $token, 'turnstileRequired' => $turnstileEnabled]);
    }
    $token = $_POST['token'] ?? null;
    $tokens = $_SESSION['contact_tokens'] ?? [];
    $issued = is_array($tokens) && is_string($token) ? ($tokens[$token] ?? null) : null;
    if (is_string($token)) {
        unset($_SESSION['contact_tokens'][$token]);
    }
    session_write_close();
    if (!is_string($token) || !preg_match('/\A[a-f0-9]{64}\z/', $token) || !is_int($issued) || $issued < time() - 600) {
        InbesContact\recordEvent($eventPath, 'csrf_rejected', $stage, null);
        respond(403, ['ok' => false]);
    }
    $stage = 'validation';
    $data = InbesContact\submission($_POST);
    $attachment = InbesContact\attachment($_FILES);
    $stage = 'rate_limit';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        throw new RuntimeException('client address unavailable');
    }
    $statePath = privateFile($config['state_file'], $root);
    $stateFile = fopen($statePath, 'r+');
    if ($stateFile === false || !flock($stateFile, LOCK_EX)) {
        throw new RuntimeException('rate state unavailable');
    }
    try {
        $raw = stream_get_contents($stateFile, 1048577);
        if ($raw === false || strlen($raw) > 1048576) {
            throw new RuntimeException('invalid rate state');
        }
        $state = json_decode($raw, true, 32, JSON_THROW_ON_ERROR);
        if (!is_array($state)) {
            throw new RuntimeException('invalid rate state');
        }
        $now = time();
        $state = array_values(array_filter($state, static fn($row) => is_array($row) && is_int($row['time'] ?? null) && $row['time'] > $now - 3600));
        $identity = hash_hmac('sha256', $ip, $config['rate_key']);
        $count = count(array_filter($state, static fn($row) => ($row['id'] ?? null) === $identity));
        if ($count >= 5 || count($state) >= 100) {
            InbesContact\recordEvent($eventPath, 'rate_limited', $stage, $reference);
            header('Retry-After: 3600');
            respond(429, ['ok' => false]);
        }
        $state[] = ['time' => $now, 'id' => $identity];
        $encoded = json_encode($state, JSON_THROW_ON_ERROR);
        rewind($stateFile);
        if (!ftruncate($stateFile, 0) || fwrite($stateFile, $encoded) !== strlen($encoded) || !fflush($stateFile)) {
            throw new RuntimeException('rate state write failed');
        }
    } finally {
        flock($stateFile, LOCK_UN);
        fclose($stateFile);
    }
    if ($turnstileEnabled) {
        $stage = 'verification';
        $verification = InbesContact\verifyTurnstile($_POST['cf-turnstile-response'] ?? null, $config['turnstile_secret'], $origin['host']);
        if ($verification !== 'accepted') {
            InbesContact\recordEvent($eventPath, $verification === 'rejected' ? 'verification_rejected' : 'verification_unavailable', $stage, $reference);
            respond($verification === 'rejected' ? 403 : 503, ['ok' => false, 'uncertain' => false, 'code' => 'verification_' . $verification]);
        }
    }
    $stage = 'mailer';
    $autoload = InbesContact\privateAutoload($config['autoload'], $root);
    require $autoload;
    $reference = bin2hex(random_bytes(8));
    $mail = configuredMailer($config);
    $mail->addAddress($config['to_address']);
    $mail->addReplyTo($data['email'], $data['name']);
    $mail->Subject = 'お問い合わせ: ' . $data['category'];
    $mail->Body = "受付番号: {$reference}\n種別: {$data['category']}\n会社名: {$data['company']}\nお名前: {$data['name']}\nメール: {$data['email']}\n電話: {$data['tel']}\n\n{$data['message']}\n";
    if ($attachment !== null) {
        $mail->addAttachment($attachment['path'], $attachment['name'], PHPMailer::ENCODING_BASE64, $attachment['mime']);
    }
    $stage = 'delivery';
    InbesContact\recordEvent($eventPath, 'delivery_started', $stage, $reference);
    $deliveryAttempted = true;
    $mail->send();
    InbesContact\recordEvent($eventPath, 'accepted', $stage, $reference);
    $replySent = false;
    if (($config['autoreply_enabled'] ?? false) === true) {
        $stage = 'reply';
        try {
            $reply = configuredMailer($config);
            $reply->addAddress($data['email']);
            $reply->Subject = '【INBES】お問い合わせを受け付けました';
            $reply->Body = "{$data['name']} 様\n\n株式会社INBESへお問い合わせいただき、ありがとうございます。\n以下の内容でお問い合わせを受け付けました。\n\n受付番号: {$reference}\nお問い合わせ種別: {$data['category']}\n\n内容を確認のうえ、担当者よりご連絡いたします。\n\n本メールは、お問い合わせフォームをご利用いただいた方へ自動でお送りしています。\nお心当たりがない場合は、このメールを破棄してください。\n\n株式会社INBES\nhttps://inbes.jp/\n";
            $reply->send();
            $replySent = true;
            InbesContact\recordEvent($eventPath, 'reply_sent', $stage, $reference);
        } catch (Throwable $error) {
            InbesContact\recordEvent($eventPath, 'reply_failed', $stage, $reference);
            // A failed acknowledgement must not turn an accepted inquiry into a retry.
        }
    }
    respond(200, ['ok' => true, 'reference' => $reference, 'replySent' => $replySent]);
} catch (InbesContact\InvalidSubmission $error) {
    InbesContact\recordEvent($eventPath, 'validation_rejected', $stage, null);
    respond(422, ['ok' => false]);
} catch (Throwable $error) {
    InbesContact\recordEvent($eventPath, 'service_failed', $stage, $reference);
    respond(503, ['ok' => false, 'uncertain' => $deliveryAttempted]);
}
