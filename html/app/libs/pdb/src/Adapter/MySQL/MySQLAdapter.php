<?php

namespace matpoppl\Pdb\Adapter\MySQL;

use matpoppl\Pdb\Adapter\AbstractAdapter;
use matpoppl\Pdb\Adapter\AdapterException;

class MySQLAdapter extends AbstractAdapter
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    protected function createDriver()
    {
        $cfg = array_merge(array(
            'host' => '127.0.0.1',
            'port' => null,
            'dbname' => null,
            'user' => null,
            'password' => null,
            'charset' => null,
            'new_link' => false,
            'flags' => null, // MYSQL_CLIENT_SSL, MYSQL_CLIENT_COMPRESS, MYSQL_CLIENT_IGNORE_SPACE, MYSQL_CLIENT_INTERACTIVE
            'init_command' => null,
        ), $this->options);

        $host = $cfg['host'] . (empty($cfg['port']) ? '' : ':' . $cfg['port']);
        $mysql = mysql_connect($host, $cfg['user'], $cfg['password'], $cfg['new_link'], $cfg['flags']);

        if (! is_resource($mysql)) {
            throw new AdapterException('mysql_connect error', 0);
        }

        if (! mysql_select_db($cfg['dbname'], $mysql)) {
            throw new AdapterException('mysql_select_db', 0);
        }

        if ($cfg['charset'] && ! mysql_set_charset($cfg['charset'], $mysql)) {
            throw new AdapterException('mysql_set_charset error', 0);
        }

        if (! $cfg['init_command']) {
            $this->execute($cfg['init_command']);
        }

        return $mysql;
    }

    public function prepare($query, array $params = null)
    {
        return new MySQLStatement($this, $query, $params);
    }

    public function query($query, array $params = null)
    {
        return $this->prepare($query, $params)->execute();
    }

    public function execute($query)
    {
        $ret = mysql_query($query, $this->getDriver());

        if (false === $ret) {
            throw $this->lastError();
        }

        return $ret;
    }

    public function ping()
    {
        return mysql_ping($this->getDriver());
    }

    public function listTables()
    {
        return array_map(function ($row) {
            return current($row);
        }, $this->query('show tables')->fetchAll());
    }

    public function lastError()
    {
        $code = mysql_errno($this->getDriver());
        return ($code < 1) ? null : new AdapterException(mysql_error($this->getDriver()), $code);
    }

    public function lastInsertId($name = null)
    {
        return mysql_insert_id($this->getDriver());
    }

    public function affectedRows()
    {
        return  mysql_affected_rows($this->getDriver());
    }
}
