<?php

namespace App\Route\Entity;

use App\Entity\RouteBaseEntity;
use App\Menu\Entity\MenuLinkEntity;
use App\EntityManager\EntityInterface;
use App\I18n\Locale\LocaleInterface;
use App\Site\SiteInterface;

class RouteEntity extends RouteBaseEntity
{
    public function bindTargetEntity(EntityInterface $entity)
    {
        if ($entity->isNewRecord()) {
            throw new \InvalidArgumentException('Route record must exist');
        }
        
        $this->setMvcPath($entity->getClassAlias() . '/' . $entity->getId());
        
        return $this;
    }
    
    public function bindMenuLink(MenuLinkEntity $menuLink)
    {
        $url = $menuLink->getUrl();
        if (strlen($url) < 1) {
            throw new \InvalidArgumentException('MenuLink URL must exist');
        }
        
        $this->setUrl($url);
        return $this;
    }
    
    public function bindLocale(LocaleInterface $locale)
    {
        $this->setLocale($locale->getId());
        return $this;
    }
    
    public function bindSite(SiteInterface $site)
    {
        $this->setSite($site->getId());
        return $this;
    }
}
