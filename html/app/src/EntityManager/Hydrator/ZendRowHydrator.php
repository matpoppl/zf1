<?php

namespace App\EntityManager\Hydrator;

use matpoppl\Hydrator\HydratorInterface;

class ZendRowHydrator implements HydratorInterface
{
    /**
     * @param \Zend_Db_Table_Row_Abstract $obj
     * @return array
     */
    public function extract($obj)
    {
        return $obj->toArray();
    }

    /**
     * @param \Zend_Db_Table_Row_Abstract $obj
     */
    public function hydrate($obj, array $data)
    {
        $obj->setFromArray($data);
    }
}
