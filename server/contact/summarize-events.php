<?php
declare(strict_types=1);
// Private, manual read-only check; this does not schedule notifications.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require __DIR__ . '/EventLog.php';
try {
    if ($argc !== 2 || realpath($argv[1]) !== $argv[1] || is_link($argv[1]) ||
        !is_file($argv[1]) || (fileperms($argv[1]) & 0077) !== 0 || (fileperms(dirname($argv[1])) & 0077) !== 0) {
        throw new RuntimeException('invalid private log');
    }
    $handle = fopen($argv[1], 'r');
    if ($handle === false || !flock($handle, LOCK_SH)) {
        throw new RuntimeException('log unavailable');
    }
    try {
        $raw = stream_get_contents($handle, 1048577);
        if ($raw === false || strlen($raw) > 1048576) throw new RuntimeException('invalid log');
    } finally {
        flock($handle, LOCK_UN); fclose($handle);
    }
    $counts = array_fill_keys(['delivery_started', 'accepted', 'reply_sent', 'reply_failed', 'service_failed', 'verification_rejected', 'verification_unavailable', 'rate_limited'], 0);
    $unresolved = [];
    foreach (explode("\n", trim($raw)) as $line) {
        if ($line === '') continue;
        $row = json_decode($line, true, 8, JSON_THROW_ON_ERROR);
        if (!is_array($row) || !array_key_exists('reference', $row) || !isset($counts[$row['event'] ?? '']) ||
            !is_string($row['time'] ?? null) || strtotime($row['time']) === false ||
            !(($row['reference'] ?? null) === null || (is_string($row['reference']) && preg_match('/\A[a-f0-9]{16}\z/', $row['reference'])))) {
            throw new RuntimeException('invalid log entry');
        }
        $counts[$row['event']]++;
        if ($row['event'] === 'delivery_started' && $row['reference'] !== null) $unresolved[$row['reference']] = $row['time'];
        if ($row['event'] === 'accepted' && $row['reference'] !== null) unset($unresolved[$row['reference']]);
    }
    echo json_encode(['events' => $counts, 'unconfirmed_deliveries' => count($unresolved), 'automatic_monitoring' => false], JSON_THROW_ON_ERROR) . "\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Private event check unavailable; no request data output.\n");
    exit(1);
}
