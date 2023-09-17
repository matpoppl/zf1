<?php

namespace matpoppl\ServiceManager\Factory;

use matpoppl\ServiceManager\ContainerInterface;

interface FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null);
}
