<?php

namespace App\Menu;

use Zend_Navigation_Container as MenuContainer;

class MenuBuilder
{
    /** @var MenuContainer */
    private $container;

    /** @var ContainerInterface[] */
    private $containers = [];

    public function __construct(MenuContainer $nav)
    {
        $this->container = $nav;
    }

    public function registerContainer(string $module, ContainerInterface $container)
    {
        $this->containers[$module] = $container;
        return $this;
    }

    public function getContainer()
    {
        $this->container->addPages($this->containers['panel']->getPages());

        return $this->container;
    }
}
