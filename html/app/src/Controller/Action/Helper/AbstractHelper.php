<?php

namespace App\Controller\Action\Helper;

/**
 * @property \Zend_Controller_Action_Helper_ActionStack $actionStack
 * @property \Zend_Controller_Action_Helper_AjaxContext $ajaxContext
 * @property \Zend_Controller_Action_Helper_AutoCompleteDojo $autoCompleteDojo
 * @property \Zend_Controller_Action_Helper_AutoCompleteScriptaculous $autoCompleteScriptaculous
 * @property \Zend_Controller_Action_Helper_Cache $cache
 * @property \Zend_Controller_Action_Helper_ContextSwitch $contextSwitch
 * @property \Zend_Controller_Action_Helper_FlashMessenger $flashMessenger
 * @property \Zend_Controller_Action_Helper_Json $json
 * @property \Zend_Controller_Action_Helper_Redirector $redirector
 * @property \Zend_Controller_Action_Helper_Url $url
 * @property \Zend_Controller_Action_Helper_ViewRenderer $viewRenderer
 * @property \App\Controller\Action\Helper\Log $log
 * @property \App\Controller\Action\Helper\Modules $modules
 * @property \App\Controller\Action\Helper\Security $security
 * @property \App\Controller\Action\Helper\Url2 $url2
 * @property \App\Auth\ActionHelper $auth
 */
abstract class AbstractHelper extends \Zend_Controller_Action_Helper_Abstract
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

    /**
     *
     * @param string $id
     * @return boolean
     */
    public function has($id)
    {
        return $this->getServiceManager()->has($id);
    }

    /**
     *
     * @param string $id
     * @return object
     */
    public function get($id)
    {
        return $this->getServiceManager()->get($id);
    }
}
