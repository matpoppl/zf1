<?php

namespace matpoppl\Pdb\Adapter;

abstract class AbstractAdapter implements AdapterInterface
{
    protected $driver = null;

    abstract protected function createDriver();

    public function getDriver()
    {
        if (null === $this->driver) {
            $this->driver = $this->createDriver();
        }

        return $this->driver;
    }
}
