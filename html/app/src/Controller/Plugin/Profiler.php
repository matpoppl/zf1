<?php

namespace App\Controller\Plugin;

use Zend_Controller_Request_Abstract as Request;
use matpoppl\Debug\Profiler as DebugProfiler;

class Profiler extends AbstractPlugin
{
    /** @var \matpoppl\Debug\Profiler */
    private $profiler;

    public function __construct()
    {
        $this->setProfiler(\Zend_Registry::get('APP_PROFILER'));
    }

    public function setProfiler(DebugProfiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function routeStartup(Request $request)
    {
        $this->profiler->mark(__METHOD__ . '()');
    }

    public function routeShutdown(Request $request)
    {
        try {
            /** @see \Zend_Controller_Router_Rewrite */
            $matched = $this->get('router')->getCurrentRouteName();
        } catch (\Zend_Controller_Router_Exception $ex) {
            $matched = 'NONE';
        }

        $appPath = $request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName();

        $this->profiler->mark(__METHOD__."() [{$matched}] {$appPath}");
    }

    public function dispatchLoopStartup(Request $request)
    {
        $this->profiler->mark(__METHOD__ . '()');
    }

    public function preDispatch(Request $request)
    {
        try {
            /** @see \Zend_Controller_Router_Rewrite */
            $matched = $this->get('router')->getCurrentRouteName();
        } catch (\Zend_Controller_Router_Exception $ex) {
            $matched = 'NONE';
        }

        $appPath = $request->getModuleName() . ':' . $request->getControllerName() . ':' . $request->getActionName();

        $this->profiler->mark(__METHOD__."() [{$matched}] {$appPath}");
    }

    public function postDispatch(Request $request)
    {
        $this->profiler->mark(__METHOD__ . '()');
    }

    public function dispatchLoopShutdown()
    {
        $this->profiler->mark(__METHOD__ . '()');
    }
}
