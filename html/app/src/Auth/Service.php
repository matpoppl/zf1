<?php

namespace App\Auth;

use Zend_Auth_Storage_Interface as StorageInterface;

class Service
{
    /** @var StorageInterface */
    private $storage = null;

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return $this->getAuth()->hasIdentity();
    }

    /**
     *
     * @return IdentityInterface|NULL
     */
    public function getIdentity()
    {
        return $this->getAuth()->getIdentity();
    }

    /**
     *
     * @return void
     */
    public function clearIdentity()
    {
        return $this->getAuth()->clearIdentity();
    }

    /**
     *
     * @param mixed $adapter
     * @return \Zend_Auth_Result
     */
    public function authenticate($adapter)
    {
        return $this->getAuth()->authenticate($adapter);
    }

    /** @return \Zend_Auth */
    private function getAuth()
    {
        if (null === $this->storage) {
            throw new Exception('Auth storage required');
        }

        return \Zend_Auth::getInstance()->setStorage($this->storage);
    }
}
