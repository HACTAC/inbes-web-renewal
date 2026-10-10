<?php
declare(strict_types=1);

// A deployment-specific private copy is required. Never insert secrets in this file.
return [
    'enabled' => false,
    'origin' => '',
    'autoload' => '',
    'state_file' => '',
    'rate_key' => '',
    // Existing empty 0600 file, inside a 0700 directory outside the web root.
    'event_file' => '',
    // Enable only after the production widget and browser sitekey are deployed.
    'turnstile_enabled' => false,
    'turnstile_secret' => '',
    'smtp_host' => '',
    'smtp_port' => 465,
    'smtp_security' => 'implicit_tls',
    'smtp_user' => '',
    'smtp_password' => '',
    'from_address' => '',
    'to_address' => '',
    'autoreply_enabled' => false,
];
