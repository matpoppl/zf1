<?php

namespace App\User\Entity;

use App\EntityManager\DbTable\AbstractDbTable;

class UserDbTable extends AbstractDbTable
{
    protected $_name = 'users';

    protected $entityClass = UserEntity::class;
}
