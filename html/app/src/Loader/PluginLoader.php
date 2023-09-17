<?php

namespace App\Loader;

use matpoppl\ServiceManager\ContainerInterface;

class PluginLoader implements \Zend_Loader_PluginLoader_Interface
{
    private $ns = [];

    /** @var \matpoppl\ServiceManager\ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addPrefixPath($prefix, $path)
    {
        $this->ns[$prefix] = rtrim($path, '\\/');
        return $this;
    }

    public function load($name)
    {
        if ($this->container->has($name)) {
            return $this->container->resolve($name);
        }

        $className = 'Zend_Application_Resource_' . ucfirst($name);

        if (class_exists($className)) {
            return $className;
        }

        var_dump([$name, $this->ns]);
        die();

        return null;
    }

    public function removePrefixPath($prefix, $path = null)
    {
        var_dump([__METHOD__, $prefix, $path]);
        die();
    }

    public function getClassName($name)
    {
        var_dump([__METHOD__, $name]);
        die();
    }

    public function isLoaded($name)
    {
        var_dump([__METHOD__, $name]);
        die();
    }
}
