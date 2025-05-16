<?php

return [
    'dbDsn' => $_ENV['DB_DSN'] ?? '',
    'dbUsername' => $_ENV['DB_USERNAME'] ?? '',
    'dbPassword' => $_ENV['DB_PASSWORD'] ?? '',
    'dbCharset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'yandexApiKey' => $_ENV['YANDEX_API_KEY'] ?? '',
];
