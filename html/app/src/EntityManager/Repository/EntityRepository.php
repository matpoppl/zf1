<?php

namespace App\EntityManager\Repository;

use App\EntityManager\DbTable\AbstractDbTable;

class EntityRepository extends AbstractEntityRepository
{
    public function __construct($entityClass, AbstractDbTable $dbTable)
    {
        $this->entityClass = $entityClass;
        parent::__construct($dbTable);
    }
}
