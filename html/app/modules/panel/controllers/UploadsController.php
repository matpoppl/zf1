<?php

use Panel\Controller\AbstractController;
use Panel\Uploads\EditType;
use App\Uploads\Entity\AttachmentEntity;

class Panel_UploadsController extends AbstractController
{
    public function indexAction()
    {
        /** @var \App\EntityManager\EntityManager $em */
        $em = $this->get('EntityManager');

        return $this->render2('uploads/index.phtml', [
            'attachments' => $em->getRepository(AttachmentEntity::class)->getDbTable()->fetchAll(),
            'paths' => $this->get('paths')->asRelativeTo('doc-root:/'),

            'uploadImagePresenter' => new \App\Uploads\Presenter\Image($this->get('paths')),
        ]);
    }

    public function editAction()
    {
        /** @var \App\EntityManager\EntityManager $em */
        $em = $this->get('EntityManager');

        /** @var \App\Uploads\Entity\AttachmentRepository $repo */
        $repo = $em->getRepository(AttachmentEntity::class);
        /** @var \App\Uploads\Entity\AttachmentEntity $entity */
        $entity = $repo->findOrCreate((int) $this->getRequest()->getParam('id', 0));

        /** @var \App\Form\Builder\Form $form */
        $form = $this->get('formBuilder')->formBuilder(EditType::class, $entity, [
            'form_options' => [
                'method' => 'post',
                'enctype' => \Zend_Form::ENCTYPE_MULTIPART,
                'url' => $this->_helper->url('edit', null, null, [
                    'id' => (int) $entity->getId(),
                ]),
            ],
        ])->getForm();

        if ($this->getRequest()->isPost()) {
            $storage = new \App\Uploads\Storage\Filesystem($this->get('paths'));
            $tempDir = $storage->createTempLocation();

            /** @var Zend_Form_Element_File $fileElem */
            $fileElem = $form->get('file');
            $fileElem->setDestination('' . $tempDir);
            $fileElem->addFilter(new \App\Uploads\Filename\FilenameFilter());
            $fileElem->addValidator(new \App\Uploads\Filename\FilenameValidator([
                'max' => 255,
            ]));

            if ($form->isValid($this->getRequest()->getPost())) {
                if ($fileElem->isUploaded()) {
                    $storage->importUpload($tempDir->append($fileElem->getValue()), $entity);
                }

                if (! $em->save($entity)) {
                    throw new \RuntimeException('Page save error');
                }

                $this->_helper->log->info("Attachment `{$entity->getId()}` saved");

                $this->_helper->flashMessenger('saved', 'success');
                return $this->redirect($this->_helper->url('edit', null, null, [
                    'id' => (int) $entity->getId(),
                ]));
            }
        }

        return $this->render2('uploads/edit.phtml', [
            'form' => $form->getView(),
        ]);
    }
}
