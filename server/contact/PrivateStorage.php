<?php
declare(strict_types=1);

namespace InbesContact;

// Apache 2.4 / LiteSpeed deployment prerequisite, not proof of HTTP enforcement.
const PRIVATE_DENY_POLICY = "Options -Indexes\nRequire all denied\n";

function storageUnavailable(): never
{
    throw new \RuntimeException('private storage unavailable');
}

/** Canonical absolute paths only; reject links, special files and shared writes. */
function storageMetadata(string $path, bool $directory, bool $secret = false): array
{
    clearstatcache(true, $path);
    $info = @lstat($path);
    if (!$info || $path === '' || $path[0] !== DIRECTORY_SEPARATOR || realpath($path) !== $path ||
        ($info['mode'] & 0170000) !== ($directory ? 0040000 : 0100000) ||
        (!$directory && $info['nlink'] !== 1) ||
        ($info['mode'] & ($secret ? 0077 : 0022)) !== 0 ||
        ($info['mode'] & 07000) !== 0 || ($info['mode'] & 0400) === 0 ||
        ($directory && ($info['mode'] & 0100) === 0)) {
        storageUnavailable();
    }
    return $info;
}

function storageWithin(string $path, string $root): bool
{
    return $path === $root || str_starts_with($path, $root . DIRECTORY_SEPARATOR);
}

/** Check every nested entry: lower .htaccess must never override the root deny. */
function storageTree(string $directory, ?string $policyRoot = null): void
{
    storageMetadata($directory, true);
    $entries = @scandir($directory);
    if ($entries === false) {
        storageUnavailable();
    }
    foreach ($entries as $name) {
        if ($name === '.' || $name === '..') {
            continue;
        }
        $path = $directory . DIRECTORY_SEPARATOR . $name;
        if (strtolower($name) === '.htaccess' && $path !== $policyRoot . '/.htaccess') {
            storageUnavailable();
        }
        $info = @lstat($path);
        if (!$info) {
            storageUnavailable();
        }
        if (($info['mode'] & 0170000) === 0040000) {
            storageTree($path, $policyRoot);
        } else {
            storageMetadata($path, false);
        }
    }
}

/** Only this fixed child of the actual document root can be used inside it. */
function protectedStorageRoot(string $documentRoot): string
{
    $root = $documentRoot . '/.inbes-private';
    storageMetadata($root, true, true);
    $policy = $root . '/.htaccess';
    $info = storageMetadata($policy, false);
    if ($info['size'] !== strlen(PRIVATE_DENY_POLICY) || @file_get_contents($policy) !== PRIVATE_DENY_POLICY) {
        storageUnavailable();
    }
    storageTree($root, $root);
    return $root;
}

function storageDocumentRoot(string $documentRoot): void
{
    if ($documentRoot === DIRECTORY_SEPARATOR) {
        storageUnavailable();
    }
    storageMetadata($documentRoot, true);
}

/** Secret config and mutable state/log: owner-only file and all private ancestors. */
function privateStorageFile(string $path, string $documentRoot): string
{
    storageDocumentRoot($documentRoot);
    storageMetadata($path, false, true);
    $parent = dirname($path);
    storageMetadata($parent, true, true);
    if (storageWithin($path, $documentRoot)) {
        $root = protectedStorageRoot($documentRoot);
        if (!storageWithin($path, $root) || $path === $root . '/.htaccess') {
            storageUnavailable();
        }
        while ($parent !== $root) {
            storageMetadata($parent, true, true);
            $parent = dirname($parent);
        }
    }
    return $path;
}

/** PHPMailer source may be 0644/0755, but never writable by group/other or linked. */
function privateAutoload(string $path, string $documentRoot): string
{
    storageDocumentRoot($documentRoot);
    storageMetadata($path, false);
    if (storageWithin($path, $documentRoot)) {
        $root = protectedStorageRoot($documentRoot);
        if (!storageWithin($path, $root) || $path === $root . '/.htaccess') {
            storageUnavailable();
        }
    } else {
        storageTree(dirname($path));
    }
    return $path;
}
