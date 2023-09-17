<?php

namespace App\Auth;

use App\Controller\Action\Helper\AbstractHelper;

class ActionHelper extends AbstractHelper
{
    /** @var Service */
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function getName()
    {
        return 'Auth';
    }

    /** @return bool */
    public function clearIdentity()
    {
        return $this->service->clearIdentity();
    }

    /** @return bool */
    public function hasIdentity()
    {
        return $this->service->hasIdentity();
    }

    /** @return mixed */
    public function getIdentity()
    {
        return $this->service->getIdentity();
    }

    /**
     *
     * @param \Zend_Auth_Adapter_Interface $adapter
     * @return \Zend_Auth_Result
     */
    public function authenticate(\Zend_Auth_Adapter_Interface $adapter)
    {
        return $this->service->authenticate($adapter);
    }

    public function direct()
    {
        return $this;
    }
}
