<?php

namespace Panel\Menu;

use App\Menu\ContainerInterface;

class Container implements ContainerInterface
{
    private $pages;

    public function __construct(array $pages)
    {
        $this->pages = $pages;
    }

    public function getPages()
    {
        return $this->pages;
    }
}
