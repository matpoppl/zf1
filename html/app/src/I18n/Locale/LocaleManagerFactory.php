<?php

namespace App\I18n\Locale;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class LocaleManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        if (null === $options) {
            $config = $container->get('config');
            $options = isset($config['i18n']) ? $config['i18n'] : null;
        }

        return new LocaleManager(new Locale($container->get('locale')), $options);
    }
}
