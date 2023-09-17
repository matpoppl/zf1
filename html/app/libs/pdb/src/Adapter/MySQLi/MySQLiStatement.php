<?php

namespace matpoppl\Pdb\Adapter\MySQLi;

use matpoppl\Pdb\StatementInteraface;
use matpoppl\Pdb\Adapter\AdapterException;

class MySQLiStatement implements StatementInteraface
{
    /** @var \mysqli_stmt */
    private $stmt;

    /** @var array|NULL */
    private $params;

    /** @var \mysqli_result */
    private $result = null;

    /** @var bool */
    private $executed = false;

    public function __construct(\mysqli_stmt $stmt, array $params = null)
    {
        $this->stmt = $stmt;
        $this->params = $params;
    }

    public function __destruct()
    {
        $this->result && $this->result->close();
        $this->stmt->close();
    }

    public function execute(array $params = null)
    {
        if (null === $params) {
            $params = $this->params;
        }

        if ($this->executed) {
            $this->result->close();
        }

        $this->executed = true;

        if (($count = count($params)) > 0) {
            $this->stmt->bind_param(str_repeat('s', $count), ...$params);
        }

        if (false === $this->stmt->execute()) {
            throw $this->stmt->lastError();
        }

        $this->result = $this->stmt->get_result();

        return $this;
    }

    public function lastError()
    {
        return ($this->stmt->errno < 1) ? null : new AdapterException($this->stmt->error, $this->stmt->errno);
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

        return $this->result->num_rows;
    }

    public function getIterator()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->result;
    }

    public function fetch()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->result->fetch_assoc();
    }

    public function fetchAll()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->result->fetch_all(\MYSQLI_ASSOC);
    }
}
