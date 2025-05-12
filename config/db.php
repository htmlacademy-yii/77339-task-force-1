<?php

$params = require __DIR__ . '/params.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => $params['dbDsn'],
    'username' => $params['dbUsername'],
    'password' => $params['dbPassword'],
    'charset' => $params['dbCharset'],
];
