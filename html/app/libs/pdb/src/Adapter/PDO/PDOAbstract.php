<?php

namespace matpoppl\Pdb\Adapter\PDO;

use matpoppl\Pdb\Adapter\AbstractAdapter;

abstract class PDOAbstract extends AbstractAdapter
{
    public function prepare($query, array $params = null)
    {
        $stmt = $this->getDriver()->prepare($query);

        if (! $stmt) {
            throw new \ErrorException(json_encode($this->getDriver()->errorInfo()));
        }

        return new PDOStatement($stmt, $params);
    }

    public function query($query, array $params = null)
    {
        return $this->prepare($query, $params)->execute();
    }
}
