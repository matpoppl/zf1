<?php

namespace matpoppl\Pdb;

interface StatementInteraface extends \Countable, \IteratorAggregate
{
    /**
     *
     * @param array $params
     * @throws \matpoppl\Pdb\Adapter\AdapterException
     * @return StatementInteraface
     */
    public function execute(array $params = null);

    /**
     *
     * @return mixed
     */
    public function getDriver();

    /**
     *
     * @return array
     */
    public function fetchAll();
}
