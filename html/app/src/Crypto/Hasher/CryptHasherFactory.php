<?php

namespace App\Crypto\Hasher;

use App\Crypto\RandomBytesInterface;
use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\Factory\FactoryInterface;

class CryptHasherFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        return new CryptHasher($container->get(RandomBytesInterface::class));
    }
}
