<?php

namespace App\EntityManager\Hydrator;

use matpoppl\Hydrator\HydratorInterface;

class NestedHydrator implements HydratorInterface
{
    /** @var HydratorInterface[] */
    private $hydrators;

    public function __construct(array $hydrators)
    {
        $this->hydrators = $hydrators;
    }

    /**
     * @param \Zend_Db_Table_Row_Abstract $obj
     * @return array
     */
    public function extract($obj)
    {
        $ret = [];

        foreach ($this->hydrators as $key => $hydrator) {
            $ret[$key] = $hydrator->extract($obj->{$key});
        }

        return $ret;
    }

    /**
     * @param \Zend_Db_Table_Row_Abstract $obj
     */
    public function hydrate($obj, array $data)
    {
        foreach ($this->hydrators as $key => $hydrator) {
            $hydrator->hydrate($obj->{$key}, $data[$key]);
        }
    }
}
