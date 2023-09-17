<?php

namespace App\Controller\Plugin;

use Zend_Controller_Request_Abstract as Request;
use App\Module\AbstractModule;

class Layout extends AbstractPlugin
{
    public function preDispatch(Request $request)
    {
        $moduleName = $request->getModuleName();
        $modules = $this->get('modules');

        if (! isset($modules->{$moduleName})) {
            return;
        }

        /** @var \App\Module\AbstractModule $module */
        $module = $modules->{$moduleName};

        if ($module instanceof AbstractModule) {
            $this->get('Layout')->setViewBasePath($module->getDir() . '/layouts', $module->getAppNamespace() . '_Layout_');
        }
    }
}
