<?php
declare(strict_types=1);

namespace InbesContact;

/** Single request, fixed HTTPS destination, bounded response, verified TLS. */
function siteverify(string $secret, string $token): array
{
    $context = stream_context_create([
        'http' => ['method' => 'POST', 'header' => "Content-Type: application/x-www-form-urlencoded\r\n", 'content' => http_build_query(['secret' => $secret, 'response' => $token]), 'timeout' => 8, 'follow_location' => 0, 'ignore_errors' => true],
        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'allow_self_signed' => false],
    ]);
    $stream = @fopen('https://challenges.cloudflare.com/turnstile/v0/siteverify', 'r', false, $context);
    if ($stream === false) {
        throw new \RuntimeException('verification unavailable');
    }
    try {
        $raw = stream_get_contents($stream, 16385);
        $meta = stream_get_meta_data($stream);
        $headers = $meta['wrapper_data'] ?? [];
        if ($raw === false || strlen($raw) > 16384 || ($meta['timed_out'] ?? false) ||
            !is_array($headers) || !preg_match('/\AHTTP\/\S+ 200(?:\s|$)/', $headers[0] ?? '')) {
            throw new \RuntimeException('verification unavailable');
        }
        $result = json_decode($raw, true, 16, JSON_THROW_ON_ERROR);
        if (!is_array($result)) {
            throw new \RuntimeException('verification unavailable');
        }
        return $result;
    } finally {
        fclose($stream);
    }
}

/** Never trust client-side success; require the configured hostname and action. */
function verifyTurnstile(mixed $token, string $secret, string $hostname, ?callable $transport = null): string
{
    if (!is_string($token) || $token === '' || strlen($token) > 2048 || preg_match('/[\x00-\x20\x7f]/', $token)) {
        return 'rejected';
    }
    try {
        $result = ($transport ?? __NAMESPACE__ . '\\siteverify')($secret, $token);
        if (!is_array($result) || !is_bool($result['success'] ?? null)) {
            return 'unavailable';
        }
        if ($result['success'] !== true) {
            return 'rejected';
        }
        return ($result['hostname'] ?? null) === $hostname && ($result['action'] ?? null) === 'contact' ? 'accepted' : 'rejected';
    } catch (\Throwable $error) {
        return 'unavailable';
    }
}
