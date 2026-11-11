<?php
// Copy this file to config.php and enter the online API URL and bearer token.
return array(
    'api_base_url' => 'https://arseniossilay.com/api/prints',
    'api_token' => 'PASTE_THE_PRINT_API_TOKEN_FROM_ONLINE_APPLICATION_CONFIG_HERE',
    'database_path' => __DIR__ . '/data/print_terminal.sqlite',
    'poll_interval_seconds' => 3,
    'printer_port' => 9100,
);
