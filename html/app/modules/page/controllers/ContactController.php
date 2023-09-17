<?php

use Page\Controller\AbstractController;
use Page\Contact\ContactForm;

class Page_ContactController extends AbstractController
{
    public function contactAction()
    {
        /** @var Zend_Controller_Request_Http $req */
        $req = $this->getRequest();

        $form = new ContactForm();

        $form->removeElement('vrfy');

        if ($req->isPost()) {
            if ($form->isValid($req->getPost())) {
                var_dump($form->getValues());
                die();
            }
        }

        $this->view->assign('form', $form);
    }
}
