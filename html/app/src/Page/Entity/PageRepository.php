<?php

namespace App\Page\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;

class PageRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = PageEntity::class;
}
