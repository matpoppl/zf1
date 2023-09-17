<?php

namespace App\EntityManager;

interface EntityInterface
{
    /** @return bool */
    public function isNewRecord();

    /** @return array [ key => value ] */
    public function getPKs();

    /** @return string */
    public function getTableName();

    /** @return string */
    public function getClassAlias();
}
