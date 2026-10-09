<?php
declare(strict_types=1);

// Private production configuration only; never publish this file.
$config = require privateFile(__DIR__ . '/production-config.php', $root);
if (!is_array($config) || ($config['autoreply_enabled'] ?? true) !== false) {
    throw new RuntimeException('production configuration unavailable');
}
$config['enabled'] = true;
$config['autoreply_enabled'] = true;
return $config;
