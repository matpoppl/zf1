<?php

$dirApp = dirname(__DIR__) . '/';

/** @see Zend_File_Transfer_Adapter_Abstract::_getTmpDir() */
$_ENV['TMP'] = $dirApp . 'var/tmp';

return [
    'resources' => [
        /** @see Zend_Application_Resource_Db */
        'db' => [
            'adapter' => 'pdo_mysql',
            'params' => [
                'host' => '127.0.0.1',
                'dbname' => 'zf1',
                'username' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASS'],
                'charset' => 'utf8mb4',
            ],
        ],
    ],
];
