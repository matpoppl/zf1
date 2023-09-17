<?php

use Page\Controller\AbstractController;

class Page_ErrorController extends AbstractController
{
    public function errorAction()
    {
        $errorMessage = 'An error occured';

        $error = $this->_request->getParam('error_handler');

        if (isset($error->exception)) {
            $ex = $error->exception;

            if ($ex instanceof Exception || $ex instanceof Throwable) {
                $this->_helper->log()->handleException($ex);
            } else {
                $this->_helper->log()->crit(''.$ex);
            }

            if ($ex instanceof Zend_Controller_Dispatcher_Exception) {
                $errorMessage = $ex->getMessage();
                $this->_response->setHttpResponseCode(400);
            } elseif ($ex instanceof Zend_Controller_Action_Exception) {
                $this->_response->setHttpResponseCode($ex->getCode());
            } else {
                $this->_response->setHttpResponseCode(500);
            }

            $this->view->assign('exception', $ex);
        } else {
            $this->_helper->log()->err('Generic error page');
            $this->_response->setHttpResponseCode(500);
        }

        $this->view->headTitle($this->view->translate($errorMessage));
    }
}
