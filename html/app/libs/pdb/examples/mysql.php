<?php

use matpoppl\Pdb\Adapter\AdapterFactory;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL & ~E_DEPRECATED);

$factory = new AdapterFactory();

$db = $factory->create([
    'type' => 'MySQL',
    'options' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'db1',
        'user' => 'user1',
        'password' => 'pass1',
        'charset' => 'utf8',
        'init_command' => 'SET names `utf8`',
    ],
]);

$firstTable = <<<EOT
CREATE TABLE IF NOT EXISTS `first_table` (
`id` INT unsigned NOT NULL AUTO_INCREMENT,
`name` VARCHAR(100) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8
EOT;

$db->execute($firstTable);

$db->execute("INSERT INTO `first_table` (`name`) VALUES ('aaa'), ('ccc'), ('bbb')");

$stmt = $db->query('SELECT * FROM `first_table` WHERE id>:id', [':id'=>2]);

foreach ($stmt as $row) {
    print_r($row);
}
