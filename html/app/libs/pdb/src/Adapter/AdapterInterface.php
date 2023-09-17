<?php

namespace matpoppl\Pdb\Adapter;

interface AdapterInterface
{
    /**
     *
     * @param string $query
     * @param array $params
     * @return \matpoppl\Pdb\StatementInteraface
     */
    public function prepare($query, array $params = null);

    /**
     *
     * @param string $query
     * @param array $params
     * @return \matpoppl\Pdb\StatementInteraface
     */
    public function query($query, array $params = null);

    /**
     *
     * @param string $query
     * @return mixed Driver specific
     */
    public function execute($query);

    /**
     *
     * @return object
     */
    public function getDriver();

    /**
     *
     * @return string[]
     */
    public function listTables();

    /**
     *
     * @param string|NULL $name Sequence name
     * @return string
     */
    public function lastInsertId($name = null);

    /**
     *
     * @return \matpoppl\Pdb\Adapter\AdapterException|NULL
     */
    public function lastError();
}
