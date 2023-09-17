<?php

namespace matpoppl\ServiceManager\Factory;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\ContainerAwareInterface;

class InvokableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $instance = new $name($options);
        if ($instance instanceof ContainerAwareInterface) {
            $instance->setContainer($container);
        }
        return $instance;
    }
}
