<?php
declare(strict_types=1);
require __DIR__ . '/EventLog.php';
require __DIR__ . '/Turnstile.php';

$checks = 0;
function check(bool $condition, string $label): void {
    global $checks;
    if (!$condition) throw new RuntimeException($label);
    $checks++;
}
$ok = static fn($secret, $token) => ['success' => true, 'hostname' => 'inbes.jp', 'action' => 'contact'];
check(InbesContact\verifyTurnstile('valid-token', 'secret', 'inbes.jp', $ok) === 'accepted', 'accepted');
foreach ([null, [], '', str_repeat('a', 2049), "token\n"] as $value) {
    check(InbesContact\verifyTurnstile($value, 'secret', 'inbes.jp', static function () { throw new RuntimeException('must not call'); }) === 'rejected', 'invalid input');
}
foreach ([['success' => false], ['success' => true, 'hostname' => 'other.jp', 'action' => 'contact'], ['success' => true, 'hostname' => 'inbes.jp', 'action' => 'other'], ['success' => true]] as $response) {
    check(InbesContact\verifyTurnstile('valid', 'secret', 'inbes.jp', static fn() => $response) === 'rejected', 'reject invalid validation');
}
check(InbesContact\verifyTurnstile('valid', 'secret', 'inbes.jp', static fn() => ['success' => 'true']) === 'unavailable', 'bad response');
check(InbesContact\verifyTurnstile('valid', 'secret', 'inbes.jp', static function () { throw new RuntimeException('private provider error'); }) === 'unavailable', 'provider down');
$calls = 0;
check(InbesContact\verifyTurnstile('valid', 'secret', 'inbes.jp', static function () use (&$calls) { $calls++; return ['success' => false, 'error-codes' => ['timeout-or-duplicate']]; }) === 'rejected' && $calls === 1, 'single-use no retry');
$directory = sys_get_temp_dir() . '/inbes-log-' . bin2hex(random_bytes(8));
mkdir($directory, 0700);
$path = $directory . '/events.jsonl';
touch($path); chmod($path, 0600);
try {
    check(InbesContact\recordEvent($path, 'accepted', 'delivery', '1234567890abcdef'), 'log success');
    $row = json_decode(trim(file_get_contents($path)), true, 8, JSON_THROW_ON_ERROR);
    check(array_keys($row) === ['time', 'event', 'stage', 'reference'], 'minimal fields');
    check(!InbesContact\recordEvent($path, 'customer@example.com', 'delivery', null), 'no uncontrolled events');
    check(!InbesContact\recordEvent($path, 'service_failed', 'SMTP password details', null), 'no uncontrolled stages');
    check(!InbesContact\recordEvent($path, 'accepted', 'delivery', "malicious\nentry"), 'no injection');
    check(!InbesContact\recordEvent(null, 'accepted', 'delivery', null), 'disabled');
    InbesContact\recordEvent($path, 'delivery_started', 'delivery', 'abcdef1234567890');
    InbesContact\recordEvent($path, 'reply_failed', 'reply', '1234567890abcdef');
    $process = proc_open([PHP_BINARY, __DIR__ . '/summarize-events.php', $path], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (!is_resource($process)) throw new RuntimeException('summary unavailable');
    $summary = stream_get_contents($pipes[1]);
    fclose($pipes[1]); fclose($pipes[2]);
    check(proc_close($process) === 0, 'summary succeeds');
    $summary = json_decode($summary, true, 8, JSON_THROW_ON_ERROR);
    check($summary['events']['accepted'] === 1 && $summary['events']['reply_failed'] === 1 && $summary['unconfirmed_deliveries'] === 1, 'detect failures and uncertain send');
    $link = $directory . '/linked.jsonl';
    symlink($path, $link);
    check(!InbesContact\recordEvent($link, 'accepted', 'delivery', null), 'reject symlinks');
    unlink($link);
    link($path, $link);
    check(!InbesContact\recordEvent($path, 'accepted', 'delivery', null), 'reject hardlinks');
    unlink($link);
    chmod($path, 0644);
    check(!InbesContact\recordEvent($path, 'accepted', 'delivery', null), 'private permissions');
    chmod($path, 0600);
    $seed = str_repeat(str_repeat('x', 127) . "\n", 8192);
    file_put_contents($path, $seed);
    check(InbesContact\recordEvent($path, 'reply_failed', 'reply', '1234567890abcdef'), 'bounded retention');
    clearstatcache(true, $path);
    check(filesize($path) <= 1048576 && str_ends_with(file_get_contents($path), "\n"), 'size bounded');
    file_put_contents($path, str_repeat('x', 1048577));
    check(!InbesContact\recordEvent($path, 'accepted', 'delivery', null), 'oversized fail safe');
} finally {
    unlink($path); rmdir($directory);
}
echo json_encode(['passed' => true, 'checks' => $checks, 'real_mail_sent' => false]) . "\n";
