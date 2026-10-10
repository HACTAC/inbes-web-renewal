<?php
declare(strict_types=1);

namespace InbesContact;

/** Bounded private operational log. No request data or exception messages. */
function recordEvent(?string $path, string $event, string $stage, ?string $reference): bool
{
    if ($path === null) {
        return false;
    }
    $events = ['delivery_started', 'accepted', 'reply_sent', 'reply_failed', 'service_failed', 'verification_rejected', 'verification_unavailable', 'rate_limited', 'request_received', 'origin_rejected', 'fetch_site_rejected', 'csrf_rejected', 'validation_rejected'];
    $stages = ['configuration', 'request', 'validation', 'rate_limit', 'verification', 'mailer', 'delivery', 'reply'];
    if (!in_array($event, $events, true) || !in_array($stage, $stages, true) ||
        ($reference !== null && !preg_match('/\A[a-f0-9]{16}\z/', $reference))) {
        return false;
    }
    clearstatcache(true, $path);
    $before = @lstat($path);
    if (!$before || realpath($path) !== $path || ($before['mode'] & 0170000) !== 0100000 ||
        ($before['mode'] & 0077) !== 0 || $before['nlink'] !== 1) {
        return false;
    }
    $handle = @fopen($path, 'r+');
    if ($handle === false) {
        return false;
    }
    try {
        if (!flock($handle, LOCK_EX)) {
            return false;
        }
        $info = fstat($handle);
        if (!$info || $info['dev'] !== $before['dev'] || $info['ino'] !== $before['ino'] || ($info['mode'] & 0170000) !== 0100000 || ($info['mode'] & 0077) !== 0 || $info['nlink'] !== 1 || $info['size'] > 1048576) {
            return false;
        }
        $old = stream_get_contents($handle, 1048577);
        if ($old === false || strlen($old) > 1048576) {
            return false;
        }
        $line = json_encode(['time' => gmdate('c'), 'event' => $event, 'stage' => $stage, 'reference' => $reference], JSON_THROW_ON_ERROR) . "\n";
        if (strlen($old) + strlen($line) <= 1048576) {
            fseek($handle, 0, SEEK_END);
            return fwrite($handle, $line) === strlen($line) && fflush($handle);
        }
        // Keep the newest complete records in this file; cap storage at 1 MiB.
        $cut = strpos($old, "\n", strlen($old) + strlen($line) - 1048576);
        $retained = ($cut === false ? '' : substr($old, $cut + 1)) . $line;
        rewind($handle);
        return fwrite($handle, $retained) === strlen($retained) && ftruncate($handle, strlen($retained)) && fflush($handle);
    } catch (\Throwable $error) {
        // Logging must never turn an accepted inquiry into a retry.
        return false;
    } finally {
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}
