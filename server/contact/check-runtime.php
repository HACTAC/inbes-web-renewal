<?php
declare(strict_types=1);
// Run by the server administrator over CLI; never add to the public release.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
$result = ['php_version' => PHP_VERSION, 'extensions' => [], 'phpmailer_version' => null];
foreach (['fileinfo', 'mbstring', 'openssl', 'zip'] as $extension) {
    $result['extensions'][$extension] = extension_loaded($extension);
}
if ($argc === 2 && is_file($argv[1]) && realpath($argv[1]) === $argv[1]) {
    require $argv[1];
    if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
        $result['phpmailer_version'] = \PHPMailer\PHPMailer\PHPMailer::VERSION;
    }
}
echo json_encode($result, JSON_THROW_ON_ERROR) . "\n";
