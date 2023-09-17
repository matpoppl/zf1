<?php

namespace App\View\Helper;

use matpoppl\ServiceManager\ContainerInterface;

class Services extends AbstractHelper
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function services($id = null)
    {
        return (null === $id) ? $this : $this->get($id);
    }
}
