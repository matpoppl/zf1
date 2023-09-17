<?php

namespace App\Site;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class SiteManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        if (null === $options) {
            $config = $container->get('config');
            $options = isset($config['site_manager']) ? $config['site_manager'] : null;
        }

        return new SiteManager($options);
    }
}
