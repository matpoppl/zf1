<?php

namespace App\Menu\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;
use App\EntityManager\DbTable\AbstractDbTable;

class MenuRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = MenuEntity::class;

    /**
     *
     * @param string $sid
     * @return \App\Menu\Entity\MenuEntity|NULL
     */
    public function findBySID($sid)
    {
        return $this->fetchEntity(['sid=?' => $sid]);
    }
}
