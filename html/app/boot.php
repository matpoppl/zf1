<?php

use matpoppl\Debug\Debugger;

require __DIR__ . '/autoload.php';

$profiler = Debugger::getInstance()->register(-1)->profiler;

$profiler->mark('autoload.php');

Zend_Registry::set('APP_PROFILER', $profiler);
