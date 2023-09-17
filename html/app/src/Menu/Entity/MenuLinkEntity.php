<?php

namespace App\Menu\Entity;

use App\Entity\MenuLinkBaseEntity;
use App\Route\Entity\RouteEntity;

class MenuLinkEntity extends MenuLinkBaseEntity
{
    public function hasAbsoluteUrl()
    {
        return false !== strpos($this->getUrl(), '//');
    }

    public function bindRoute(RouteEntity $route)
    {
        if ($route->isNewRecord()) {
            throw new \InvalidArgumentException('Route record must exist');
        }

        $route->setUrl($this->getUrl());
        $this->setRoute($route->getId());

        return $this;
    }
}
