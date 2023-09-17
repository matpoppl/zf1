<?php

namespace matpoppl\Pdb\Adapter\SQLite;

use matpoppl\Pdb\Adapter\AdapterException;
use matpoppl\Pdb\Adapter\AbstractAdapter;

class SQLiteAdapter extends AbstractAdapter
{
    private $pathname;
    private $flags;
    private $encryption_key;

    public function __construct(array $options)
    {
        $this->pathname = $options['pathname'] ?? null;
        $this->flags = $options['flags'] ?? \SQLITE3_OPEN_READWRITE | \SQLITE3_OPEN_CREATE;
        $this->encryption_key = $options['encryption_key'] ?? null;
    }

    /**
     *
     * @return \SQLite3
     */
    public function createDriver()
    {
        $driver = new \SQLite3($this->pathname, $this->flags, $this->encryption_key);
        $driver->enableExceptions(true);

        // prevent output
        $this->pathname = null;
        $this->flags = null;
        $this->encryption_key = null;

        return $driver;
    }

    public function prepare($query, array $params = null)
    {
        $stmt = $this->getDriver()->prepare($query);
        return new SQLiteStatement($this, $stmt, $params);
    }

    public function query($query, array $params = null)
    {
        return $this->prepare($query, $params)->execute();
    }

    public function execute($query)
    {
        return $this->getDriver()->exec($query);
    }

    public function listTables()
    {
        $stmt = $this->query('SELECT name FROM sqlite_master');
        return array_column($stmt->fetchAll(), 'name');
    }

    public function lastError()
    {
        $code = $this->getDriver()->lastErrorCode();
        return $code < 1 ? null : (new AdapterException($this->getDriver()->lastErrorMsg(), $code));
    }

    public function lastInsertId($name = null)
    {
        return $this->getDriver()->lastInsertRowID();
    }
}
