<?php
declare(strict_types=1);
require __DIR__ . '/PrivateStorage.php';

$checks = 0;
function assertStorage(bool $ok, string $label): void
{
    global $checks;
    if (!$ok) throw new RuntimeException($label);
    $checks++;
}
function rejectsStorage(callable $operation, string $label): void
{
    $rejected = false;
    try { $operation(); } catch (RuntimeException $error) {
        $rejected = $error->getMessage() === 'private storage unavailable';
    }
    assertStorage($rejected, $label);
}
function storageTestFile(string $path, string $contents = 'nonsecret fixture', int $mode = 0600): void
{
    file_put_contents($path, $contents);
    chmod($path, $mode);
}
function cleanupStorage(string $path): void
{
    if (is_link($path) || !is_dir($path)) { unlink($path); return; }
    foreach (scandir($path) as $name) {
        if ($name !== '.' && $name !== '..') cleanupStorage($path . '/' . $name);
    }
    rmdir($path);
}
$base = sys_get_temp_dir() . '/inbes-storage-' . bin2hex(random_bytes(8));
mkdir($base, 0700);
$web = $base . '/web';
mkdir($web, 0755);
$outside = $base . '/outside';
mkdir($outside, 0700);
$private = $web . '/.inbes-private';
mkdir($private, 0700);
$secret = $private . '/config.php';
$policy = $private . '/.htaccess';
storageTestFile($secret);
storageTestFile($outside . '/config.php');
storageTestFile($web . '/config.php');
storageTestFile($policy, InbesContact\PRIVATE_DENY_POLICY, 0644);
$vendor = $private . '/vendor';
mkdir($vendor, 0755);
storageTestFile($vendor . '/autoload.php', '<?php // nonsecret fixture', 0644);
mkdir($vendor . '/package', 0755);
storageTestFile($vendor . '/package/mail.php', '<?php // nonsecret fixture', 0644);
$privateCheck = static fn() => InbesContact\privateStorageFile($secret, $web);
$autoloadCheck = static fn() => InbesContact\privateAutoload($vendor . '/autoload.php', $web);
try {
    assertStorage($privateCheck() === $secret, 'fixed protected root');
    assertStorage($autoloadCheck() === $vendor . '/autoload.php', 'normal vendor permissions');
    assertStorage(InbesContact\privateStorageFile($outside . '/config.php', $web) === $outside . '/config.php', 'outside compatibility');
    rejectsStorage(static fn() => InbesContact\privateStorageFile($web . '/config.php', $web), 'public config rejected');
    rejectsStorage(static fn() => InbesContact\privateAutoload($web . '/config.php', $web), 'public autoload rejected');
    rejectsStorage(static fn() => InbesContact\privateStorageFile($private . '/../.inbes-private/config.php', $web), 'dot traversal rejected');
    rejectsStorage(static fn() => InbesContact\privateStorageFile($secret, $web . '/'), 'noncanonical root rejected');
    chmod($secret, 0644);
    rejectsStorage($privateCheck, 'readable secrets rejected');
    chmod($secret, 0600);
    chmod($private, 0755);
    rejectsStorage($privateCheck, 'readable private root rejected');
    rejectsStorage($autoloadCheck, 'autoload also requires private root');
    chmod($private, 0700);
    storageTestFile($policy, "Options -Indexes\nRequire all granted\n", 0644);
    rejectsStorage($privateCheck, 'modified policy rejected');
    storageTestFile($policy, InbesContact\PRIVATE_DENY_POLICY, 0644);
    rename($policy, $private . '/policy.saved');
    rejectsStorage($privateCheck, 'missing policy rejected');
    rename($private . '/policy.saved', $policy);
    chmod($policy, 0664);
    rejectsStorage($privateCheck, 'writable policy rejected');
    chmod($policy, 0644);
    link($policy, $private . '/policy.link');
    rejectsStorage($privateCheck, 'hardlinked policy rejected');
    unlink($private . '/policy.link');
    storageTestFile($vendor . '/package/.htaccess', 'Require all granted', 0644);
    rejectsStorage($privateCheck, 'nested policy blocks secrets too');
    rejectsStorage($autoloadCheck, 'nested policy blocks vendor');
    unlink($vendor . '/package/.htaccess');
    storageTestFile($vendor . '/package/.HTACCESS', 'Require all granted', 0644);
    rejectsStorage($autoloadCheck, 'case variant policy rejected');
    unlink($vendor . '/package/.HTACCESS');
    symlink($outside . '/config.php', $vendor . '/linked.php');
    rejectsStorage($privateCheck, 'nested symlink blocks protected tree');
    unlink($vendor . '/linked.php');
    link($secret, $outside . '/linked.php');
    rejectsStorage($privateCheck, 'hardlinked secrets rejected');
    unlink($outside . '/linked.php');
    chmod($vendor . '/package', 0775);
    rejectsStorage($autoloadCheck, 'writable ancestor rejected');
    chmod($vendor . '/package', 0755);
    chmod($vendor . '/package/mail.php', 0664);
    rejectsStorage($autoloadCheck, 'writable vendor dependency rejected');
    chmod($vendor . '/package/mail.php', 0644);
    link($vendor . '/package/mail.php', $outside . '/linked.php');
    rejectsStorage($autoloadCheck, 'hardlinked dependency rejected');
    unlink($outside . '/linked.php');
    mkdir($private . '/state', 0755);
    storageTestFile($private . '/state/rate.json', '[]');
    rejectsStorage(static fn() => InbesContact\privateStorageFile($private . '/state/rate.json', $web), 'mutable state parent owner only');
    chmod($private . '/state', 0700);
    assertStorage(InbesContact\privateStorageFile($private . '/state/rate.json', $web) === $private . '/state/rate.json', 'private nested state');
    symlink($private, $web . '/alias');
    rejectsStorage(static fn() => InbesContact\privateStorageFile($web . '/alias/config.php', $web), 'symlink ancestor rejected');
    unlink($web . '/alias');
    mkdir($outside . '/vendor', 0755);
    storageTestFile($outside . '/vendor/autoload.php', '<?php // fixture', 0644);
    assertStorage(InbesContact\privateAutoload($outside . '/vendor/autoload.php', $web) === $outside . '/vendor/autoload.php', 'outside vendor compatibility');
    chmod($outside . '/vendor', 0775);
    rejectsStorage(static fn() => InbesContact\privateAutoload($outside . '/vendor/autoload.php', $web), 'outside writable vendor rejected');
    chmod($outside . '/vendor', 0755);
    assertStorage(file_get_contents(__DIR__ . '/private-template/.htaccess') === InbesContact\PRIVATE_DENY_POLICY, 'deployment policy exact bytes');
    echo 'private storage checks: ' . $checks . " passed\n";
} finally {
    cleanupStorage($base);
}
