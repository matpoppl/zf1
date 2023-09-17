<?php

use matpoppl\DbEntityGenerator\ClassGenerator;
use matpoppl\DbEntityGenerator\ClassWriter;
use matpoppl\DbEntityGenerator\MysqlTypeMap;
use matpoppl\DbEntityGenerator\Formatter\CamelCase;

require __DIR__ . '/../autoload.php';

$config = [
    'attachments' => [
        'class_name' => 'Attachment',
    ],
    'menu_links' => [
        'class_name' => 'MenuLink',
    ],
    'menus' => [
        'class_name' => 'Menu',
    ],
    'pagemetas' => [
        'class_name' => 'PageMeta',
    ],
    'pages' => [
        'class_name' => 'Page',
    ],
    'routes' => [
        'class_name' => 'Route',
    ],
    'users' => [
        'class_name' => 'User',
    ],
];

$db = new PDO('mysql:host=127.0.0.1;dbname=zf1;charset=utf8mb4', 'root', 'qwe', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$nameFmt = new CamelCase();

$tables = [];

foreach ($db->query('SHOW TABLES')->fetchAll() as $row) {
    $tableName = reset($row);

    $tableDesc = $db->query('DESCRIBE `'.$tableName.'`')->fetchAll();

    $keys = array_column($tableDesc, 'Field');

    $tables[$tableName] = array_combine($keys, $tableDesc);
}

$dbTypeMapper = new MysqlTypeMap();
$writer = new ClassWriter();

$writer->addPRS4('App\\', __DIR__ . '/../../html/app/src');

foreach ($tables as $tableName => $tableDesc) {
    $className = empty($config[$tableName]['class_name']) ? ucfirst($nameFmt->to($tableName)) : $config[$tableName]['class_name'];

    $classGen = new ClassGenerator($className . 'BaseEntity', 'App\\Entity');

    $classGen->addUse('App\\EntityManager\\AbstractEntity')
    ->addExtend('AbstractEntity')
    ->setAbstract(true);

    $classGen->createMethod('getTableName')->setBody('return ' . var_export($tableName, true) . ';');

    $classGen->createMethod('getPKs');

    $classGen->createMethod('getClassAlias')->setBody('return ' . var_export($className, true) . ';');

    $pks = [];

    foreach ($tableDesc as $fieldName => $fieldDesc) {

        // CANT BE PRIVATE when using FETCH_CLASS on inherited class
        $prop = $classGen->createProperty($nameFmt->to($fieldName))->setVisibility('protected');

        list($phpType, $dbType, $typeOpts) = $dbTypeMapper->extractDbType($fieldDesc['Type']);

        $prop->getDocblock()->add('var', $phpType);

        if ('enum' === $dbType) {
            $prop->setValue(reset($typeOpts));
        }

        // GETTER
        $classGen->createMethod($nameFmt->to('get_' . $fieldName))
        ->setBody('return $this->'.$prop->getName().';')
        ->getDocblock()->add('return', $dbTypeMapper->extract($fieldDesc['Type']));

        // SETTER
        $classGen->createMethod($nameFmt->to('set_' . $fieldName))
        ->setParameters(['$'.$prop->getName()])
        ->setBody('$this->'.$prop->getName().' = $'.$prop->getName().'; return $this;')
        ->getDocblock()->add('param', $dbTypeMapper->extract($fieldDesc['Type']), $prop->getName())
        ->add('return', 'self');

        if (false !== strpos($fieldDesc['Key'], 'PRI')) {
            $pks[] = $prop->getName();
        }
    }

    $classGen->getMetohd('getPKs')->setBody("return [" . implode(", ", array_map(function ($field) {
        return "'{$field}' => \$this->{$field}";
    }, $pks)) . "];");

    echo $classGen->getFQName() . "\n";

    $writer->write($classGen);
}
