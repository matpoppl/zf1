<?php

namespace App\Controller;

use Zend_Registry;
use App\View\Helper\Services;
use App\I18n\Locale\SiteLocale;

/**
 * @method \Zend_Controller_Request_Http getRequest()
 * @method \Zend_Controller_Response_Http getResponse()
 * @property \Zend_Controller_Request_Http $_request
 * @property \Zend_Controller_Response_Http $_response
 * @property \App\Controller\Action\Helper\AbstractHelper $_helper
 * @property \App\View\View $view
 */
abstract class AbstractController extends \Zend_Controller_Action
{
    /**
     * @return \App\Bootstrap
     */
    public function getApp()
    {
        return $this->getFrontController()->getParam('bootstrap');
    }

    /** @return \matpoppl\ServiceManager\ServiceManagerInterface */
    public function getServiceManager()
    {
        return $this->getApp()->getServiceManager();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($id)
    {
        return $this->getServiceManager()->has($id);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($id)
    {
        return $this->getServiceManager()->get($id);
    }

    /** @return \App\EntityManager\EntityManager */
    public function getEntityManager()
    {
        return $this->get('EntityManager');
    }
    
    /** @return \App\Site\SiteInterface */
    public function getSite()
    {
        return new \App\Site\Site([
            $this->getSiteLocale(),
        ]);
    }
    
    /** @return \App\I18n\Locale\SiteLocaleInterface */
    public function getSiteLocale()
    {
        // @TODO $record { id, other-options }
        return new SiteLocale(1, $this->get('i18n')->getLocale());
    }

    /** @return \App\Form\Builder\FormBuilderService */
    public function getFormBuilder()
    {
        return $this->get('formBuilder');
    }

    /** @return \Zend_Session_Namespace */
    protected function getSession($name = null)
    {
        $ns = new \Zend_Session_Namespace(__NAMESPACE__);
        if (null === $name) {
            return $ns;
        }
        return isset($ns->{$name}) ? $ns->{$name} : null;
    }

    public function init()
    {
        Zend_Registry::get('APP_PROFILER')->mark(__METHOD__);
    }

    public function preDispatch()
    {
        parent::preDispatch();
        Zend_Registry::get('APP_PROFILER')->mark(__METHOD__);

        $locale = '' . $this->get('i18n')->getLocale();
        $this->view->translate()->setLocale($locale);

        $this->view->registerHelper(new \App\View\Helper\Security(), 'security');
        $this->view->registerHelper(new Services($this->getServiceManager()), 'services');
    }

    public function postDispatch()
    {
        parent::postDispatch();
        Zend_Registry::get('APP_PROFILER')->mark(__METHOD__);

        $this->view->registerHelper(new \App\View\Helper\FlashMessenger($this->getHelper('flashMessenger'), ['success', 'error', 'info', 'notice']), 'flashMessenger');
    }

    public function render2($template, array $data = null, \Zend_Controller_Response_Abstract $response = null)
    {
        if (null !== $data) {
            $this->view->assign($data);
        }

        if (null !== $response) {
            $this->setResponse($response);
        }

        return $this->getHelper('viewRenderer')->renderScript($template);
    }
}
