<?php

namespace App\View\Helper;

class FlashMessenger extends AbstractHelper implements \Countable, \IteratorAggregate
{
    /** @var \Zend_Controller_Action_Helper_FlashMessenger */
    private $flashMessenger;

    /** @var string[] */
    private $namespaces;

    public function __construct(\Zend_Controller_Action_Helper_FlashMessenger $flashMessenger, array $namespaces)
    {
        $this->flashMessenger = $flashMessenger;
        $this->namespaces = $namespaces;
    }

    public function flashMessenger()
    {
        return $this;
    }

    public function direct()
    {
        return $this;
    }

    public function getMessages($ns = null)
    {
        if (null !== $ns) {
            return $this->flashMessenger->getIterator($ns);
        }

        $ret = [];

        foreach ($this->namespaces as $ns) {
            if ($this->flashMessenger->hasMessages($ns)) {
                $ret[$ns] = $this->flashMessenger->getIterator($ns);
            }
        }

        return new \ArrayIterator($ret);
    }

    public function getIterator()
    {
        return $this->getMessages();
    }

    public function count($ns = null)
    {
        if (null !== $ns) {
            return $this->flashMessenger->count($ns);
        }

        $count = 0;

        foreach ($this->namespaces as $ns) {
            $count += $this->flashMessenger->count($ns);
        }

        return $count;
    }
}
