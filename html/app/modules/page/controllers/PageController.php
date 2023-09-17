<?php

use Page\Controller\AbstractController;

class Page_PageController extends AbstractController
{
    public function viewAction()
    {
        /** @var \App\Page\Metadata\Entity\PageMetaEntity $meta */
        $meta = $this->getRequest()->getParam('pageMeta');

        /** @var \App\EntityManager\EntityManager $em */
        $em = $this->get('EntityManager');

        $page = $em->findOrCreate($meta->getModelClass(), $meta->getModelId());

        return $this->render2('page/view.phtml', [
            'page' => $page,
        ]);
    }
}
