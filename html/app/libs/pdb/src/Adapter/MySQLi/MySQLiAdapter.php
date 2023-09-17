<?php

namespace matpoppl\Pdb\Adapter\MySQLi;

use matpoppl\Pdb\Adapter\AbstractAdapter;
use matpoppl\Pdb\Adapter\AdapterException;

class MySQLiAdapter extends AbstractAdapter
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /** @return\mysqli */
    protected function createDriver()
    {
        $cfg = array_merge(array(
            'host' =>  null,
            'port' => null,
            'dbname' => null,
            'user' => null,
            'password' => null,
            'socket' => null,
            'charset' => null,
        ), $this->options);

        $mysqli = new \mysqli($cfg['host'], $cfg['user'], $cfg['password'], $cfg['dbname'], $cfg['port'], $cfg['socket']);

        if ($mysqli->connect_errno > 0) {
            throw new AdapterException($mysqli->connect_error, $mysqli->connect_errno);
        }

        if ($cfg['charset'] && ! $mysqli->set_charset($cfg['charset'])) {
            throw new AdapterException($mysqli->error, $mysqli->errno);
        }

        return $mysqli;
    }

    public function prepare($query, array $params = null)
    {
        $stmt = $this->getDriver()->prepare($query);

        if (false === $stmt) {
            throw $this->lastError();
        }

        return new MySQLiStatement($stmt, $params);
    }

    public function query($query, array $params = null)
    {
        return $this->prepare($query, $params)->execute();
    }

    public function execute($query)
    {
        if (false === $this->getDriver()->real_query($query)) {
            throw $this->lastError();
        }

        return true;
    }

    public function ping()
    {
        return $this->getDriver()->ping();
    }

    public function listTables()
    {
        return array_map(function ($row) {
            return current($row);
        }, $this->query('show tables')->fetchAll());
    }

    public function lastError()
    {
        $mysqli = $this->getDriver();
        return ($mysqli->errno < 1) ? null : new AdapterException($mysqli->error, $mysqli->errno);
    }

    public function lastInsertId($name = null)
    {
        return $this->getDriver()->insert_id;
    }

    public function affectedRows()
    {
        return $this->getDriver()->affected_rows;
    }
}
