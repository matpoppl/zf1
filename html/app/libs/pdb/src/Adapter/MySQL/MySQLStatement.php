<?php

namespace matpoppl\Pdb\Adapter\MySQL;

use matpoppl\Pdb\StatementInteraface;

class MySQLStatement implements StatementInteraface
{
    /** @var MySQLAdapter */
    private $adapter;

    /** @var array|NULL */
    private $params;

    /** @var string */
    private $query;

    /** @var resource */
    private $result = null;

    /** @var bool */
    private $executed = false;

    public function __construct(MySQLAdapter $adapter, $query, array $params = null)
    {
        $this->adapter = $adapter;
        $this->query = $query;
        $this->params = $params;
    }

    public function execute(array $params = null)
    {
        if (null === $params) {
            $params = $this->params;
        }

        if ($this->executed) {
            mysql_free_result($this->result);
        }

        $this->executed = true;

        $query = $this->query;

        if (is_array($params)) {
            foreach ($params as $name => $val) {
                $query = str_replace($name, mysql_real_escape_string($val, $this->adapter->getDriver()), $query);
            }
        }

        $this->result = mysql_query($query, $this->adapter->getDriver());

        if (false === $this->result) {
            $ex = $this->adapter->lastError() ?: new \RuntimeException('Unknown error');
            throw $ex;
        }

        return $this;
    }

    public function getDriver()
    {
        return $this->result;
    }

    public function count()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return mysql_num_rows($this->result);
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

        return mysql_fetch_row($this->result);
    }

    public function fetchAll()
    {
        if (! $this->executed) {
            $this->execute();
        }

        $ret = array();

        while (false !== ($row = mysql_fetch_assoc($this->result))) {
            $ret[] = $row;
        }

        return $ret;
    }
}
