<?php

namespace App\User\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;

class UserRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = UserEntity::class;
}
