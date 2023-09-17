<?php

namespace App\Controller\Action\Helper;

class Security extends AbstractHelper
{
    public function getName()
    {
        return 'Security';
    }

    public function direct()
    {
        return $this;
    }

    public function verifyCSRF()
    {
        $elem = new \Zend_Form_Element_Hash('csrf', [
            'salt' => 'security',
        ]);
        return $elem->isValid($this->getRequest()->getPost('csrf'), $this->getRequest()->getPost());
    }
}
