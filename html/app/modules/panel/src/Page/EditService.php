<?php

namespace Panel\Page;

use App\EntityManager\EntityManagerInterface;
use App\EntityManager\EntityInterface;
use App\Page\Entity\PageEntity;
use App\Page\Metadata\Entity\PageMetaEntity;
use App\Route\PathFormatter;

class EditService
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var PathFormatter */
    private $pathFormatter;

    public function __construct(EntityManagerInterface $em, PathFormatter $pathFormatter)
    {
        $this->em = $em;
        $this->pathFormatter = $pathFormatter;
    }

    public function loadPage($pageID)
    {
        return $this->em->findOrCreate(PageEntity::class, $pageID);
    }

    public function loadPageMeta(EntityInterface $page)
    {
        return $this->em->findOrCreate(PageMetaEntity::class, $page->getId(), 'page', 'pl');
    }

    public function save(EntityInterface $page, EntityInterface $meta)
    {
        if (! $this->em->save($page)) {
            throw new \RuntimeException('Page save error');
        }

        if ($meta->isNewRecord()) {
            if (empty($meta->getUri())) {
                $meta->setUri($this->pathFormatter->format($page->getName()));
            }
            if (empty($meta->getTitle())) {
                $meta->setTitle($page->getName());
            }
        }

        $meta->setModelId($page->getId());
        $meta->setModelClass('page');
        $meta->setLang('pl');

        if (! $this->em->save($meta)) {
            throw new \RuntimeException('Meta save error');
        }
    }
}
