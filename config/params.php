<?php
$secretKeys = parse_ini_file(__DIR__ . '/app-config.ini');

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'apiKeyGeocoder' => $secretKeys['apiKeyGeocoder'],
    'vkClientId' => $secretKeys['vkClientId'],
    'vkClientSecret' => $secretKeys['vkClientSecret'],
];
