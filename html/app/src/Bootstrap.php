<?php

namespace App;

use matpoppl\ServiceManager\ServiceManager;
use matpoppl\ServiceManager\ContainerInterface;
use App\I18n\Locale\LocaleManager;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap implements ContainerInterface
{
    /** @var \matpoppl\ServiceManager\ServiceManagerInterface */
    private $serviceManager;

    private $arePluginResourceNamesNormalized = false;

    /**
     *
     * @param \Zend_Application $application
     */
    public function __construct($application)
    {
        $this->serviceManager = new ServiceManager($application->getOption('service_manager'));
        $this->serviceManager->setService('bootstrap', $this);
        $this->serviceManager->setFallbackContainer($this);

        /*
                $application->setOptions([
                    'pluginLoader' => new \App\Loader\PluginLoader($this->serviceManager),
                ]);
                \Zend_Controller_Action_HelperBroker::setPluginLoader(new \App\Loader\PluginLoader($this->serviceManager));
        */

        $this->serviceManager->setService('config', $application->getOptions());

        parent::__construct($application);
    }

    public function run()
    {
        \Zend_Registry::get('APP_PROFILER')->mark(__METHOD__);
        parent::run();
        \Zend_Registry::get('APP_PROFILER')->mark(__METHOD__);
    }

    protected function _initControllerHelpers()
    {
        \Zend_Controller_Action_HelperBroker::addPath(__DIR__ . '/Controller/Action/Helper', 'App\\Controller\\Action\\Helper');
    }

    protected function _initI18n()
    {
        $this->getServiceManager()->get(LocaleManager::class)->register($this);
    }

    /** @return \matpoppl\ServiceManager\ServiceManagerInterface */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function has(string $id): bool
    {
        return $this->hasPluginResource($id);
    }

    public function get(string $id)
    {
        return $this->bootstrap($id)->getResource($id);
    }

    /*
    public function has($id)
    {
        return $this->serviceManager->has($id) || $this->hasPluginResource($id);
    }

    public function get($id)
    {
        $normalized = $this->resolveResourceName($id);

        if (array_key_exists($normalized, $this->_pluginResources)) {
            return $this->bootstrap($normalized)->getResource($normalized);
        }

        return $this->serviceManager->get($id);
    }

    public function resolveResourceName($resource)
    {
        if (! $this->arePluginResourceNamesNormalized) {
            $this->arePluginResourceNamesNormalized = true;
            $this->_pluginResources = array_change_key_case($this->_pluginResources, \CASE_LOWER);
        }

        $resource = strtolower($resource);
        return array_key_exists($resource, $this->_pluginResources) ? $resource : null;
    }

    public function hasPluginResource($resource)
    {
        $normalized = $this->resolveResourceName($resource);
        return array_key_exists($normalized, $this->_pluginResources) || $this->serviceManager->has($resource);
    }

    public function getPluginResource($resource)
    {
        $normalized = $this->resolveResourceName($resource);

        if (! array_key_exists($normalized, $this->_pluginResources)) {
            return $this->serviceManager->get($resource);
        }

        if (is_array($this->_pluginResources[$normalized])) {
            $this->_pluginResources[$normalized] = $this->serviceManager->build($normalized, $this->_pluginResources[$normalized]);
        }

        return $this->_pluginResources[$normalized];
    }
*/
}
