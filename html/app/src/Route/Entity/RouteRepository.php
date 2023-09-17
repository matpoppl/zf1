<?php

namespace App\Route\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;

class RouteRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = RouteEntity::class;

    /**
     *
     * @param int $site
     * @param string $url
     * @return \App\Route\Entity\RouteEntity|NULL
     */
    public function fetchBySiteUrl($site, $url)
    {
        return $this->fetchEntity([
            'site=?' => (int) $site,
            'url=?' => $url,
        ]);
    }
}
