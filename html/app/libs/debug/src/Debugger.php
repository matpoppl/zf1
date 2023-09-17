<?php

namespace matpoppl\Debug;

class Debugger
{
    /** @var Profiler */
    public $profiler;
    /** @var Logger */
    public $logger;

    /** @var bool */
    private $hasErrors = false;
    /** @var int */
    private $errorMask = 0;

    private function __construct()
    {
        $this->profiler = new Profiler();
        $this->profiler->mark(__METHOD__);
        $this->logger = new Logger();
    }

    public function register($errorMask = 0)
    {
        $this->errorMask = $errorMask;
        error_reporting(0);
        set_error_handler([$this, 'handleError'], $errorMask);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
        return $this;
    }

    public function handleError($type, $msg, $file, $line)
    {
        $this->hasErrors = true;
        $this->logger->handleError($type, $msg, $file, $line, null, self::trace(1));
    }

    public function handleException($ex)
    {
        $this->hasErrors = true;
        $this->logger->handleException($ex);
    }

    public function handleShutdown()
    {
        $this->profiler->mark(__METHOD__);

        $lastError = error_get_last();

        if (is_array($lastError) && ($this->errorMask & $lastError['type'])) {
            $this->handleError($lastError['type'], $lastError['message'], $lastError['file'], $lastError['line']);
        }

        if (! $this->hasErrors) {
            return;
        }

        $this->profiler->print();
        $this->logger->print();
    }

    /** @return \matpoppl\Debug\Debugger */
    public static function trace($offset = 0, $limit = 99)
    {
        $ret = '';

        // skip current
        $offset++;
        $limit += $offset;

        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $i => $trace) {
            if ($i < $offset) {
                continue;
            }
            if ($i > $limit) {
                break;
            }

            $ret .= ''
                . (array_key_exists('class', $trace) ? $trace['class'] . $trace['type'] : '')
                . (array_key_exists('function', $trace) ? $trace['function'].'()' : '')
                . (array_key_exists('file', $trace) ? $trace['file'] . ':' . $trace['line'] : '')
                . "\n";
        }

        return $ret;
    }

    /** @return \matpoppl\Debug\Debugger */
    public static function &getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }
        return $instance;
    }
}
