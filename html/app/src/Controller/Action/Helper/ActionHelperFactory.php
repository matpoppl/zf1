<?php

namespace App\Controller\Action\Helper;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class ActionHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $className = 'Zend_Controller_Action_Helper_' . ucfirst($name);
        return new $className($options);
    }
}
