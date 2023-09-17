<?php

namespace App\Module;

abstract class AbstractModule extends \Zend_Application_Module_Bootstrap
{
    /** @return string */
    abstract public function getDir();

    /** @return \matpoppl\ServiceManager\ServiceManagerInterface */
    public function getServiceManager()
    {
        return $this->getApplication()->getServiceManager();
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
