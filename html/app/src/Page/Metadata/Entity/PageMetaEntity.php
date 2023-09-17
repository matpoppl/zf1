<?php

namespace App\Page\Metadata\Entity;

use App\Entity\PageMetaBaseEntity;
use App\EntityManager\EntityInterface;
use App\Page\Entity\PageEntity;
use App\I18n\Locale\SiteLocaleInterface;

class PageMetaEntity extends PageMetaBaseEntity
{
    /**
     *
     * @param object $entity
     * @throws \UnexpectedValueException
     * @return \App\Page\Metadata\Entity\PageMetaEntity
     */
    public function bindEntity(EntityInterface $entity, SiteLocaleInterface $locale)
    {
        if ($entity->isNewRecord()) {
            throw new \UnexpectedValueException('Entity record must exist');
        }

        $this->setModelId($entity->getId());
        $this->setModelClass($entity->getClassAlias());
        $this->setLocale($locale->getId());

        if (empty($this->getTitle()) && $entity instanceof PageEntity) {
            $this->setTitle($entity->getName());
        }

        return $this;
    }
}
