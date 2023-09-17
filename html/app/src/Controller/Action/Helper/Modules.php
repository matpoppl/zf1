<?php

namespace App\Controller\Action\Helper;

class Modules extends AbstractHelper
{
    /**
     *
     * @param string|NULL $moduleName
     * @return \App\Module\AbstractModule|NULL
     */
    public function getModule($moduleName = null)
    {
        if (null === $moduleName) {
            $moduleName = $this->getRequest()->getModuleName();
        }

        $modules = $this->get('modules');

        return isset($modules->{$moduleName}) ? $modules->{$moduleName} : null;
    }

    public function direct($moduleName = null)
    {
        return (null === $moduleName) ? $this : $this->getModule($moduleName);
    }
}
