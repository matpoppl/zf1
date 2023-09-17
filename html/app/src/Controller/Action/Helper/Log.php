<?php

namespace App\Controller\Action\Helper;

use Zend_Log as Logger;

/**
 * @method \Zend_Log log() log(string $message, int $priority, $extras = null)
 * @method \Zend_Log emerg() emerg(string $message, $extras = null)
 * @method \Zend_Log alert() alert(string $message, $extras = null)
 * @method \Zend_Log crit() crit(string $message, $extras = null)
 * @method \Zend_Log err() err(string $message, $extras = null)
 * @method \Zend_Log warn() warn(string $message, $extras = null)
 * @method \Zend_Log notice() notice(string $message, $extras = null)
 * @method \Zend_Log info() info(string $message, $extras = null)
 * @method \Zend_Log debug() debug(string $message, $extras = null)
 */
class Log extends AbstractHelper
{
    /** @return \Zend_Log */
    public function getLogger()
    {
        return $this->get('log');
    }

    public function __call($name, $args)
    {
        switch ($name) {
            case 'log':
            case Logger::DEBUG:
            case Logger::INFO:
                break;
            default:
                \matpoppl\Debug\Debugger::getInstance()->handleException(new \ErrorException($args[0]));
                break;
        }
        return $this->getLogger()->{$name}(...$args);
    }

    /**
     *
     * @param \Exception $ex
     */
    public function handleException($ex)
    {
        \matpoppl\Debug\Debugger::getInstance()->handleException($ex);
        return $this->getLogger()->crit($ex->getCode() .'# '. $ex->getMessage() ."\n". $ex->getFile() .':'. $ex->getLine() ."\n". $ex->getTraceAsString());
    }

    public function direct($message = null, $priority = null, $extras = null)
    {
        return (null === $message) ? $this : $this->getLogger()->log($message, $priority, $extras);
    }
}
