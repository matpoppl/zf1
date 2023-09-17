<?php

namespace App\Application\Resource;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class ResourceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $className = 'Zend_Application_Resource_' . ucfirst($name);
        $options['bootstrap'] = $container->get('bootstrap');
        return new $className($options);
    }
}
