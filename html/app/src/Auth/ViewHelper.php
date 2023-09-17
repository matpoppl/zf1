<?php

namespace App\Auth;

class ViewHelper extends \Zend_View_Helper_Abstract
{
    public function hasIdentity()
    {
        return \Zend_Auth::getInstance()->hasIdentity();
    }

    /** @return \App\Auth\IdentityInterface */
    public function getIdentity()
    {
        return \Zend_Auth::getInstance()->getIdentity();
    }

    public function identity()
    {
        return $this;
    }
}
