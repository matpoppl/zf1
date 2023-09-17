<?php

namespace App\Auth;

use App\Controller\Plugin\AbstractPlugin;
use Zend_Controller_Request_Abstract as AbstractRequest;
use Zend_Controller_Request_Http as Request;
use Zend_Auth_Storage_Session as SessionStorage;

class ControllerPlugin extends AbstractPlugin
{
    /** @var array */
    private $options;

    /** @var Service */
    private $service;

    /** @var \Zend_Acl */
    private $acl = null;

    public function __construct(Service $service, array $options)
    {
        $this->service = $service;
        $this->options = $options;
    }

    /** @return \Zend_Acl */
    public function getAcl()
    {
        if (null === $this->acl) {
            $factory = new AclFactory();
            $this->acl = $factory->create($this->options);
        }

        return $this->acl;
    }

    public function routeStartup(AbstractRequest $request)
    {
        if (($request instanceof Request)) {
            $this->service->setStorage(new SessionStorage());
            $this->check($request);
        }
    }

    public function check(Request $request)
    {
        $resource = rtrim($request->getRequestUri(), '/') . '/';

        if (0 !== strpos($resource, '/panel/')) {
            return;
        }

        while (strlen($resource) > 1 && ! $this->getAcl()->has($resource)) {
            $resource = dirname($resource) . '/';
            ;
        }

        if ($this->service->hasIdentity()) {
            $ident = $this->service->getIdentity();

            if (! ($ident instanceof IdentityInterface)) {
                throw new Exception('Unsupported identity type');
            }
            $roles = $ident->getRoles();
        } else {
            $roles = ['anon'];
        }

        foreach ($roles as $role) {
            if ($this->getAcl()->isAllowed($role, $resource)) {
                // ok
                return;
            }
        }

        // redirect
        $request->setDispatched(false);
        $request->setRequestUri('/panel/signin');
    }
}
