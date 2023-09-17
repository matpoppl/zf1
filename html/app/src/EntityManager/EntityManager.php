<?php

namespace App\EntityManager;

use App\EntityManager\Repository\RepositoryInterface;
use App\EntityManager\Repository\RepositoryFactory;
use App\EntityManager\DbTable\DbTableFactory;

class EntityManager implements EntityManagerInterface
{
    /** @var DbTableFactory */
    private $dbTableFactory;

    /** @var RepositoryFactory */
    private $repositoryFactory = null;

    /** @var RepositoryInterface[] */
    private $repositories = [];

    /** @var array */
    private $options;

    public function __construct(DbTableFactory $dbTableFactory, array $options = null)
    {
        $this->dbTableFactory = $dbTableFactory;
        $this->options = $options ?: [];
    }

    public function getRepositoryFactory()
    {
        if (null === $this->repositoryFactory) {
            $this->repositoryFactory = new RepositoryFactory($this->dbTableFactory, $this->options['repositories'] ?? []);
        }

        return $this->repositoryFactory;
    }

    /**
     *
     * @param string $id
     * @return RepositoryInterface
     */
    public function getRepository(string $id): RepositoryInterface
    {
        while (array_key_exists($id, $this->options['aliases'])) {
            $id = $this->options['aliases'][$id];
        }

        if (! array_key_exists($id, $this->repositories)) {
            $this->repositories[$id] = $this->getRepositoryFactory()->create($id);
        }

        return $this->repositories[$id];
    }

    /**
     *
     * @param string $id
     * @return RepositoryInterface
     */
    public function __call($name, $args)
    {
        $id = array_shift($args);
        return $this->getRepository($id)->{$name}(...$args);
    }

    public function save(EntityInterface $entity)
    {
        //$repo = $this->getRepository($entity->getTableName());
        $repo = $this->getRepository(get_class($entity));
        return $repo->save($entity);
    }

    public function delete(EntityInterface $entity)
    {
        $repo = $this->getRepository($entity->getTableName());
        return $repo->delete($entity);
    }

    public function findOrCreate(string $id, ...$pks): EntityInterface
    {
        return $this->getRepository($id)->findOrCreate(...$pks);
    }
}
