<?php

namespace matpoppl\Pdb;

class ArrayStatement implements StatementInteraface
{
    /** @var array */
    private $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    /**
     *
     * @param array $params
     * @throws \matpoppl\Pdb\Adapter\AdapterException
     * @return StatementInteraface
     */
    public function execute(array $params = null)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     *
     * @return object
     */
    public function getDriver()
    {
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     *
     * @return mixed
     */
    public function fetch()
    {
        return empty($this->rows) ? null : reset($this->rows);
    }

    /**
     *
     * @return array
     */
    public function fetchAll()
    {
        return $this->rows;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->rows);
    }

    public function count()
    {
        return count($this->rows);
    }
}
