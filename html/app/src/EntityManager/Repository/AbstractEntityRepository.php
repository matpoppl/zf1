<?php

namespace App\EntityManager\Repository;

use App\EntityManager\EntityInterface;
use matpoppl\Hydrator\ClassMethodsHydrator;
use App\EntityManager\DbTable\AbstractDbTable;
use App\EntityManager\AbstractEntity;

/**
 * @method \Zend_Db_Table_Rowset find()
 * @method \Zend_Db_Table_Rowset fetchAll($where = null, $order = null, $count = null, $offset = null)
 * @method \Zend_Db_Table_Row fetchRow($where = null, $order = null, $offset = null)
 */
abstract class AbstractEntityRepository implements RepositoryInterface
{
    /** @var AbstractDbTable */
    private $dbTable = null;

    /** @var string */
    protected $entityClass = null;

    public function __construct(AbstractDbTable $dbTable)
    {
        $this->setDbTable($dbTable);
    }

    public function setDbTable(AbstractDbTable $dbTable)
    {
        $this->dbTable = $dbTable;
        return $this;
    }

    /**
     *
     * @return AbstractDbTable
     */
    public function getDbTable()
    {
        return $this->dbTable;
    }

    public function __call($name, $args)
    {
        $rows = $this->getDbTable()->{$name}(...$args);

        // hmmm return as is ?
        if ($rows instanceof \PDOStatement) {
            return $rows;
        }

        if ($rows instanceof \Zend_Db_Table_Row) {
            return $this->createEntity($rows->toArray(), false);
        }

        if (! ($rows instanceof \Zend_Db_Table_Rowset)) {
            throw new \UnexpectedValueException('Unsupported rowset class');
        }

        $ret = new \SplFixedArray(count($rows));
        foreach ($rows as $i => $row) {
            $ret[$i] = $this->createEntity($row->toArray(), false);
        }

        return $ret;
    }

    public function fetchAllEntities($where = null, $order = null, $count = null, $offset = null)
    {
        $rows = $this->getDbTable()->fetchAll($where, $order, $count, $offset);

        $ret = new \SplFixedArray(count($rows));
        foreach ($rows as $i => $row) {
            $ret[$i] = $this->createEntity($row->toArray(), false);
        }
        return $ret;
    }

    /**
     *
     * @param string|array|\Zend_Db_Table_Select|NULL $where
     * @param string|array|NULL $order
     * @param int|NULL $offset
     * @return \App\EntityManager\AbstractEntity|NULL
     */
    public function fetchEntity($where = null, $order = null, $offset = null)
    {
        $rows = $this->getDbTable()->fetchRow($where, $order, $offset);
        return null === $rows ? $rows : $this->createEntity($rows->toArray(), false);
    }

    public function save(EntityInterface $entity)
    {
        $repo = $this->getDbTable();

        $hydrator = new ClassMethodsHydrator();

        $cols = $repo->info('cols');
        $cols = array_combine($cols, $cols);

        $data = array_intersect_key($hydrator->extract($entity), $cols);

        $pks = $repo->info('primary');
        $pks = array_combine($pks, $pks);

        if ($entity->isNewRecord()) {
            $ids = (array) $repo->insert($data);

            if (empty($ids)) {
                return false;
            }

            $set = [];
            foreach ($pks as $key) {
                $set[$key] = array_shift($ids);
            }

            $hydrator->hydrate($entity, $set);

            if ($entity instanceof AbstractEntity) {
                $entity->setIsNewRecord(false);
            }
        } else {
            // @TODO USE Zend Row Class
            $where = array_intersect_key($data, $pks);
            $data = array_diff_key($data, $pks);

            foreach ($where as $key => $val) {
                $where[$key . '=?'] = $val;
                unset($where[$key]);
            }

            $repo->update($data, $where);
        }

        return true;
    }

    public function delete(EntityInterface $entity)
    {
    }

    public function findEntity(...$pks)
    {
        foreach ($this->find(...$pks) as $entity) {
            return $entity;
        }

        return null;
    }

    /**
     *
     * @param array $data
     * @param boolean $isNewRecord
     * @throws \BadMethodCallException
     * @return \App\EntityManager\AbstractEntity
     */
    public function createEntity(array $data = null, $isNewRecord = true)
    {
        if (null === $this->entityClass) {
            throw new \BadMethodCallException('Entity class required');
        }

        $className = $this->entityClass;
        $entity = new $className();

        $entity->setIsNewRecord($isNewRecord);

        if (null !== $data) {
            (new ClassMethodsHydrator())->hydrate($entity, $data);
        }

        return $entity;
    }

    /**
     *
     * @param mixed $pk0
     * @param mixed $pk1|NULL
     * @return \App\EntityManager\EntityInterface
     */
    public function findOrCreate(...$pks)
    {
        if (empty(array_filter($pks))) {
            return $this->createEntity();
        }

        $entity = $this->findEntity(...$pks);

        if (null === $entity) {
            //throw new \DomainException('Row not found');
            $entity = $this->createEntity();
        }

        return $entity;
    }
}
