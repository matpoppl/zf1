<?php

namespace matpoppl\ServiceManager\Factory;

use matpoppl\ServiceManager\ContainerInterface;

interface AbstractFactoryInterface extends FactoryInterface
{
    public function canCreate(ContainerInterface $container, $name);
}
