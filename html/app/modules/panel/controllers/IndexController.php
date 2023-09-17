<?php

use Panel\Controller\AbstractController;

class Panel_IndexController extends AbstractController
{
    public function indexAction()
    {
        return $this->redirect($this->_helper->url->url(['controller' => 'page'], 'panel/index'));
    }
}
