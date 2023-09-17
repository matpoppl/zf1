<?php

namespace App\EntityManager\Repository;

use App\EntityManager\EntityInterface;

interface RepositoryInterface
{
    /**
     *
     * @param EntityInterface $entity
     * @throws \Exception
     * @return bool
     */
    public function save(EntityInterface $entity);

    /**
     *
     * @param EntityInterface $entity
     * @throws \Exception
     * @return bool
     */
    public function delete(EntityInterface $entity);

    /**
     *
     * @param string|int $pks
     * @return EntityInterface
     */
    public function findOrCreate(...$pks);

    /**
     *
     * @param string|int $pks
     * @return \App\EntityManager\DbTable\AbstractDbTable
     */
    public function getDbTable();

    /**
     *
     * @param array|NULL $data
     * @param boolean $isNewRecord
     * @return EntityInterface
     */
    public function createEntity(array $data = null, $isNewRecord = true);
}
