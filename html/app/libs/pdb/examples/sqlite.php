<?php

use matpoppl\Pdb\Adapter\AdapterFactory;

require __DIR__ . '/../vendor/autoload.php';

$factory = new AdapterFactory();

$sqlite = $factory->create([
    'type' => 'SQLite3',
    'options' => [
        'pathname' => __DIR__ . '/sample.sqlite',
        //'flags' => SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE,
    ],
]);

$firstTable = <<<EOT
CREATE TABLE IF NOT EXISTS `first_table` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name` VARCHAR(100) NOT NULL
)
EOT;

$sqlite->execute($firstTable);

$secondTable = <<<EOT
CREATE TABLE IF NOT EXISTS `second_table` (
`id` INTEGER PRIMARY KEY AUTOINCREMENT,
`name2` VARCHAR(100) NOT NULL
)
EOT;

$sqlite->execute($secondTable);

$sqlite->execute("INSERT INTO `first_table` (`name`) VALUES ('aaa'), ('ccc'), ('bbb')");

$stmt = $sqlite->query('SELECT * FROM `first_table` WHERE id>:id', [':id'=>2]);

var_dump(count($stmt));
print_r($stmt->fetchAll());
