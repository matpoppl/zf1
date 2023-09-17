<?php

use App\EntityManager\EntityInterface;
use App\EntityManager\Hydrator;
use Panel\Controller\AbstractController;
use Panel\Page\EditService;
use Panel\Page\EditType;
use matpoppl\Hydrator\ClassMethodsHydrator;
use App\Route\PathFormatter;
use App\I18n\Transliterator\AsciiTransliterator;

class Panel_PageController extends AbstractController
{
    public function indexAction()
    {
        $em = $this->getEntityManager();

        return $this->render2('page/index.phtml', [
            'rows' => $em->getRepository('pages')->getDbTable()->fetchAll(),
        ]);
    }

    public function editAction()
    {
        $reqID = (int) $this->getRequest()->getParam('id', 0);

        $url = $this->_helper->url('edit', null, null, [
            'id' => $reqID,
        ]);

        $urlFormatter = new PathFormatter(new AsciiTransliterator($this->getSiteLocale(), $this->getSiteLocale()->getCharset()));

        $editService = new EditService($this->get('EntityManager'), $urlFormatter);

        /** @var \App\Page\Entity\PageEntity $page */
        /** @var \App\Page\Metadata\Entity\PageMetaEntity $meta */
        $data = (object)[
            'model' => $page = $editService->loadPage($reqID),
            'meta' => $meta = $editService->loadPageMeta($page),
        ];

        $form = $this->get('formBuilder')->formBuilder(EditType::class, $data, [
            'form_options' => [
                'method' => 'post',
                'action' => $url,
            ],
            'hydrator' => new Hydrator\NestedHydrator([
                'model' => new ClassMethodsHydrator(),
                'meta' => new ClassMethodsHydrator(),
            ]),
        ])->getForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                if (empty($meta->getTitle())) {
                    $meta->setTitle($page->getName());
                }

                $editService->save($page, $meta);

                $this->_helper->log->info("Page `" . implode('/', $page->getPKs()) . "` saved");

                $url = $this->_helper->url('edit', null, null, $page->getPKs());

                $this->_helper->flashMessenger('saved', 'success');
                return $this->redirect($url);
            }
        }

        return $this->render2('page/edit.phtml', [
            'form' => $form->getView(),
        ]);
    }

    public function deleteAction()
    {
        if (! $this->getHelper('Security')->verifyCSRF()) {
            throw new Zend_Controller_Action_Exception('CSRF error', 400);
        }

        if (! $this->getRequest()->getPost('confirmed')) {
            $this->_helper->flashMessenger('confirmation required', 'error');
            return $this->redirect($this->_helper->url('index'));
        }

        /** @var \App\EntityManager\EntityManager $em */
        $em = $this->get('EntityManager');
        /** @var \App\EntityManager\Repository\EntityRepository $repo */
        $repo = $em->getRepository('pages');

        /** @TODO delete METADATA */

        foreach ($this->getRequest()->getPost('id') as $id) {
            foreach ($repo->find($id) as $row) {
                if ($row instanceof Zend_Db_Table_Row) {
                    $row->delete();
                } elseif ($row instanceof EntityInterface) {
                    $em->delete($row);
                }
            }
        }

        $this->_helper->flashMessenger('deleted', 'success');
        return $this->redirect($this->_helper->url('index'));
    }
}
