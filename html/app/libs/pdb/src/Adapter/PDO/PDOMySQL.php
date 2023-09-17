<?php

namespace matpoppl\Pdb\Adapter\PDO;

use matpoppl\Pdb\Adapter\AdapterException;

class PDOMySQL extends PDOAbstract
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /** @return \PDO */
    protected function createDriver()
    {
        $dsnOpts = array_filter(array_intersect_key($this->options, array(
            'host' => '127.0.0.1',
            'port' => null,
            'dbname' => null,
            'unix_socket' => null,
            'charset' => null,
        )));

        $dsn = 'mysql:' . http_build_query(array_filter($dsnOpts), null, ';');

        $cfg = array_merge(array(
            'user' => null,
            'password' => null,
            'driver_options' => array(),
        ), $this->options);

        $driverOptions = $cfg['driver_options'] + array(
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        );

        return new \PDO($dsn, $cfg['user'], $cfg['password'], $driverOptions);
    }

    public function listTables()
    {
        return array_map(function ($row) {
            return current($row);
        }, $this->query('show tables')->fetchAll());
    }

    public function lastError()
    {
        $info = $this->createDriver()->errorInfo();
        return $info[1] < 1 ? null : (new AdapterException($info[2], $info[0] . '# ' . $info[1]));
    }

    public function lastInsertId($name = null)
    {
        return $this->getDriver()->lastInsertId($name);
    }

    public function execute($query)
    {
        return $this->getDriver()->exec($query);
    }
}
