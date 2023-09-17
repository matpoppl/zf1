<?php

namespace App\Page\Metadata\Entity;

use App\EntityManager\Repository\AbstractEntityRepository;
use App\I18n\Locale\SiteLocaleInterface;

class PageMetaRepository extends AbstractEntityRepository
{
    /** @var string */
    protected $entityClass = PageMetaEntity::class;

    /**
     *
     * @param object $entity
     * @param SiteLocaleInterface $locale
     * @return PageMetaEntity[]
     */
    public function findByEntity($entity, SiteLocaleInterface $locale)
    {
        return $this->fetchEntity([
            'model_id=?' => $entity->getId(),
            'model_class=?' => $entity->getClassAlias(),
            'locale=?' => $locale->getId(),
        ]);
    }

    /**
     *
     * @param object $entity
     * @param SiteLocaleInterface $locale
     * @return PageMetaEntity
     */
    public function findByEntityOrCreate($entity, SiteLocaleInterface $locale)
    {
        $meta = $entity->isNewRecord() ? null : $this->findByEntity($entity, $locale);

        if (null !== $meta) {
            return $meta;
        }

        return new PageMetaEntity();
    }

    /**
     *
     * @param string $entity
     * @return PageMetaEntity|NULL
     */
    public function findByUri($uri)
    {
        return null;
        return $this->fetchEntity(['uri=?' => $uri]);
    }
}
