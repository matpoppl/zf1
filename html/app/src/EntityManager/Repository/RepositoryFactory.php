<?php

namespace App\EntityManager\Repository;

use App\EntityManager\DbTable\DbTableFactory;

class RepositoryFactory
{
    /** @var DbTableFactory */
    private $dbTableFactory;

    /** @var string[] */
    private $aliases;

    /** @var array[] */
    private $repositories;

    public function __construct(DbTableFactory $dbTableFactory, array $repositories)
    {
        $this->dbTableFactory = $dbTableFactory;
        $this->repositories = $repositories;
    }

    public function create($options)
    {
        if (is_string($options)) {
            if (array_key_exists($options, $this->repositories)) {
                $options = $this->repositories[$options];
            } else {
                $options = [
                    'table' => $options,
                ];
            }
        }

        if (! array_key_exists('table', $options)) {
            throw new \UnexpectedValueException('Table definition required');
        }

        $dbTable = $this->dbTableFactory->create($options['table']);

        if (array_key_exists('type', $options)) {
            $className = $options['type'];
            $repo = new $className($dbTable, $options);
        } else {
            $repo = new EntityRepository('UNKNOWN_MODEL_CLASS', $dbTable);
        }

        return $repo;
    }
}
