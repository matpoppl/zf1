<?php

namespace App\Loader;

class ResourceLoader extends \Zend_Loader_Autoloader_Resource
{
    public function __construct()
    {
        /*
        parent::__construct([
            'namespace' => 'App\\',
            'basePath' => __DIR__ . '/..',
        ]);
        */
    }

    public function addResourceTypes(array $types)
    {
        var_dump([__METHOD__, $types]);
        die();
    }

    public function addResourceType($type, $path, $namespace = null)
    {
        var_dump([__METHOD__, $type, $path, $namespace]);
        die();
    }

    public function getClassPath($class)
    {
        var_dump([__METHOD__, $class]);
        die();
    }

    public function autoload($class)
    {
        var_dump([__METHOD__, $class]);
        die();
    }

    public function getResourceTypes()
    {
        var_dump([__METHOD__]);
        die();
    }

    public function hasResourceType($type)
    {
        var_dump([__METHOD__, $type]);
        die();
    }

    public function setDefaultResourceType($type)
    {
        var_dump([__METHOD__, $type]);
        die();
    }

    public function load($resource, $type = null)
    {
        var_dump([__METHOD__, $resource, $type]);
        die();
    }
}
