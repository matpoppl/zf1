<?php

namespace App\EntityManager;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;
use App\EntityManager\DbTable\DbTableFactory;

class EntityMananagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        if (null === $options) {
            $config = $container->get('config');
            $options = $config['entity_manager'] ?? null;
        }

        $dbTableFactory = new DbTableFactory($container->get('Db'));

        return new EntityManager($dbTableFactory, $options);
    }
}
