<?php

namespace App\Uploads\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;

class AttachmentRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = AttachmentEntity::class;
}
