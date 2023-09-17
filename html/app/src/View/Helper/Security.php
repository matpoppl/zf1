<?php

namespace App\View\Helper;

class Security extends AbstractHelper
{
    public function security()
    {
        return $this;
    }

    public function renderCSRFElement()
    {
        $elem = new \Zend_Form_Element_Hash('csrf', [
            'salt' => 'security',
            'decorators' => ['ViewHelper'],
        ]);
        return $elem->render();
    }
}
