<?php

namespace matpoppl\Paths;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class PathsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        if (null === $options) {
            $config = $container->get('config');
            $options = $config['paths'];
        }

        return new Paths($options);
    }
}
