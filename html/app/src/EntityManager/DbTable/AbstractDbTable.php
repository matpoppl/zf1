<?php

namespace App\EntityManager\DbTable;

abstract class AbstractDbTable extends \Zend_Db_Table_Abstract
{
    /** @var string */
    protected $entityClass = null;

    /**
     *
     * @param string|array|\Zend_Db_Table_Select|NULL $where
     * @param string|array|NULL $order
     * @param string|array|NULL $count
     * @param int|NULL $offset
     * @return \Zend_Db_Table_Select
     */
    private function prepareSelect($where = null, $order = null, $count = null, $offset = null)
    {
        if (!($where instanceof \Zend_Db_Table_Select)) {
            $select = $this->select(\Zend_Db_Table::SELECT_WITH_FROM_PART);

            if ($where !== null) {
                $this->_where($select, $where);
            }

            if ($order !== null) {
                $this->_order($select, $order);
            }

            if ($count !== null || $offset !== null) {
                $select->limit($count, $offset);
            }
        } else {
            $select = $where;
        }

        return $select;
    }

    /**
     *
     * @param string $column
     * @param string|array $where
     * @return string
     */
    public function queryColumn($column, $where)
    {
        $stmt = $this->prepareSelect($where)
        ->reset(\Zend_Db_Select::COLUMNS)->columns($column)
        ->query()->getDriverStatement();

        return $stmt->fetchColumn();
    }

    /**
     *
     * @param string|array|\Zend_Db_Table_Select|NULL $where
     * @param string|array|NULL $order
     * @param string|array|NULL $count
     * @param int|NULL $offset
     * @return \PDOStatement
     */
    public function queryAll($where = null, $order = null, $count = null, $offset = null)
    {
        $stmt = $this->prepareSelect($where, $order, $count, $offset)->query()->getDriverStatement();

        if (null !== $this->entityClass && $stmt instanceof \PDOStatement) {
            $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->entityClass, [null, false]);
        }

        return $stmt;
    }
}
