<?php

define('PROFILER_START_TIME', microtime(true));
define('PROFILER_START_MEMORY', memory_get_usage());

require __DIR__ . '/app/boot.php';

(new Zend_Application('development', require __DIR__ . '/app/configs/zend.php'))->bootstrap()->run();
