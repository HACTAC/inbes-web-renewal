<?php
declare(strict_types=1);

// Private test configuration only; never publish this file.
$config = require privateFile(__DIR__ . '/test-config.php', $root);
if (!is_array($config) || ($config['autoreply_enabled'] ?? true) !== false) {
    throw new RuntimeException('test configuration unavailable');
}
$config['enabled'] = true;
return $config;
