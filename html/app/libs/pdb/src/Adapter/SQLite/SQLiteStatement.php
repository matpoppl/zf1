<?php

namespace matpoppl\Pdb\Adapter\SQLite;

use matpoppl\Pdb\StatementInteraface;

class SQLiteStatement implements StatementInteraface
{
    /** @var \SQLite3Stmt */
    private $stmt;

    /** @var array|NULL */
    private $params;

    /** @var bool */
    private $executed = false;

    /** @var \SQLite3Result */
    private $result = null;

    /** @var SQLiteAdapter */
    private $adapter;

    public function __construct(SQLiteAdapter $adapter, \SQLite3Stmt $stmt, array $params = null)
    {
        $this->adapter = $adapter;
        $this->stmt = $stmt;
        $this->params = $params;
    }

    public function __destruct()
    {
        if (! $this->result) {
            $this->result->finalize();
        }
    }

    public function execute(array $params = null)
    {
        if (null === $params) {
            $params = $this->params;
        }

        if ($this->executed) {
            $this->stmt->clear();
            $this->result->finalize();
        }

        $this->executed = true;

        if (null !== $params) {
            foreach ($params as $name => $val) {
                $this->stmt->bindValue($name, $val);
            }
        }

        $this->result = $this->stmt->execute();

        if (false === $this->result) {
            throw $this->adapter->lastError();
        }

        return $this;
    }

    public function getDriver()
    {
        return $this->stmt;
    }

    public function count()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return count($this->fetchAll());
    }

    public function getIterator()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return new \ArrayIterator($this->fetchAll());
    }

    public function fetch()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->result->fetchArray(\SQLITE3_ASSOC);
    }

    public function fetchAll()
    {
        if (! $this->executed) {
            $this->execute();
        }

        $ret = array();

        while (false !== ($row = $this->result->fetchArray(\SQLITE3_ASSOC))) {
            $ret[] = $row;
        }

        return $ret;
    }
}
