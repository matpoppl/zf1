<?php

namespace App\EntityManager\Repository;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\AbstractFactoryInterface;
use App\EntityManager\DbTable;

class AbstractRepositoryFactory implements AbstractFactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $adapter = $container->get('db');

        return new EntityRepository(new DbTable([
            'name' => $name,
            'adapter' => $adapter,
        ]));
    }

    public function canCreate(ContainerInterface $container, $name)
    {
        return 'Pages' === $name;
    }
}
