<?php

use Page\Controller\AbstractController;

class Page_IndexController extends AbstractController
{
    public function indexAction()
    {
    }

    public function localeChangeAction()
    {
        /** @var Zend_Controller_Request_Http $req */
        $req = $this->getRequest();

        /** @var Zend_Locale $locale */
        $locale = $this->get('locale');
        $locale->setLocale($req->getParam('locale'));

        $this->getSession()->locale = $locale->toString();

        /** @var Zend_Controller_Action_Helper_Url $url */
        $url = $this->_helper->url;
        return $this->redirect($url->url([], 'page/home'));
    }
}
