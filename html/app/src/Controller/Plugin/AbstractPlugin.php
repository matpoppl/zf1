<?php

namespace App\Controller\Plugin;

abstract class AbstractPlugin extends \Zend_Controller_Plugin_Abstract
{
    /** @return \App\Bootstrap */
    public function getApp()
    {
        return \Zend_Controller_Front::getInstance()->getParam('bootstrap');
    }

    /** @return \matpoppl\ServiceManager\ServiceManagerInterface */
    public function getServiceManager()
    {
        return $this->getApp()->getServiceManager();
    }

    public function has($id)
    {
        return $this->getServiceManager()->has($id);
    }

    public function get($id)
    {
        return $this->getServiceManager()->get($id);
    }
}
