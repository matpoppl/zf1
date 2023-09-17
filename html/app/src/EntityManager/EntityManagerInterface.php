<?php

namespace App\EntityManager;

use App\EntityManager\Repository\RepositoryInterface;

interface EntityManagerInterface
{
    /**
     *
     * @param string $id
     * @return RepositoryInterface
     */
    public function getRepository(string $id): RepositoryInterface;

    /**
     *
     * @param EntityInterface $entity
     */
    public function save(EntityInterface $entity);

    /**
     *
     * @param EntityInterface $entity
     */
    public function delete(EntityInterface $entity);

    /**
     *
     * @param string $id
     * @param mixed $pk0
     * @param mixed $pk1
     * @return EntityInterface
     */
    public function findOrCreate(string $id, ...$pks): EntityInterface;
}
