<?php

use Panel\Controller\AbstractController;
use App\Auth\Adapter\DbAdapter;
use App\Crypto\Hasher\PasswordHasherInterface;
use Panel\Guest\SigninType;

class Panel_GuestController extends AbstractController
{
    public function init()
    {
        $this->view->layout()->setViewBasePath(__DIR__ . '/../layouts', 'Panel_Layout_');
    }

    public function signinAction()
    {
        Zend_Session::start();

        $requestUri = $this->getRequest()->getPathInfo();
        $signinUri = $this->_helper->url->url([], 'panel-signin', true);

        $form = $this->get('formBuilder')->formBuilder(SigninType::class, null, [
            'form_options' => [
                'legend' => 'Sign in form',
                'method' => 'post',
                'action' => $requestUri,
            ],
        ])->getForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $adapter = new DbAdapter($form->getValue('username'), $form->getValue('password'), $this->get(PasswordHasherInterface::class));

                $result = $this->_helper->auth()->authenticate($adapter);

                if ($result->isValid()) {
                    if ($form->getValue('rememberMe')) {
                        Zend_Session::rememberMe(86400 * 365);
                    } else {
                        Zend_Session::regenerateId();
                    }

                    if ($requestUri === $signinUri) {
                        return $this->_helper->redirector->gotoRoute([], 'panel/home');
                    }
                    return $this->_helper->redirector->gotoUrl($requestUri);
                }

                $form->addErrorMessages($result->getMessages());

                $msg = $form->getValue('username') . ': ' . implode("\n", $result->getMessages());

                $this->_helper->log()->notice($msg);
            } else {
                // @TODO more verbose
                $this->_helper->log()->notice('invalid form request');
            }

            $this->getResponse()->setHttpResponseCode(400);
        }

        return $this->render2('guest/signin.phtml', [
            'form' => $form->getView(),
        ]);
    }
}
