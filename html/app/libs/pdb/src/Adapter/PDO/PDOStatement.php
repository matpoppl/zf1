<?php

namespace matpoppl\Pdb\Adapter\PDO;

use matpoppl\Pdb\StatementInteraface;

class PDOStatement implements StatementInteraface
{
    /** @var \PDOStatement */
    private $stmt;

    /** @var array|NULL */
    private $params;

    /** @var bool */
    private $executed = false;

    public function __construct(\PDOStatement $stmt, array $params = null)
    {
        $this->stmt = $stmt;
        $this->params = $params;
    }

    public function execute(array $params = null)
    {
        if (null === $params) {
            $params = $this->params;
        }

        if (! $this->executed) {
            $this->stmt->closeCursor();
        }

        $this->executed = true;

        $ok = (null === $params) ? $this->stmt->execute() : $this->stmt->execute($params);

        if (! $ok) {
            throw new \ErrorException(json_encode($this->stmt->errorInfo()));
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

        return $this->stmt->rowCount();
    }

    public function getIterator()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->stmt;
    }

    public function fetchAll()
    {
        if (! $this->executed) {
            $this->execute();
        }

        return $this->stmt->fetchAll();
    }
}
