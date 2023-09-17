<?php

namespace App\EntityManager;

abstract class AbstractEntity implements EntityInterface
{
    /** @var bool */
    private $_isNewRecord = true;

    public function __construct($data = null, $isNewRecord = true)
    {
        $this->_isNewRecord = !! $isNewRecord;
    }

    public function isNewRecord()
    {
        return $this->_isNewRecord;
    }

    public function setIsNewRecord($isNewRecord)
    {
        $this->_isNewRecord = $isNewRecord;
        return $this;
    }
}
