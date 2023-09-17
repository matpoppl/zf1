<?php

namespace matpoppl\Debug;

class Profiler
{
    private $logs = [];

    public function mark($msg)
    {
        $this->logs[] = [$msg, microtime(true), memory_get_usage()];
    }

    public function print()
    {
        if (empty($this->logs)) {
            return;
        }

        $prevTm = $firstTm = (defined('PROFILER_START_TIME') ? PROFILER_START_TIME : 0);
        $prevMem = $firstMem = (defined('PROFILER_START_MEMORY') ? PROFILER_START_MEMORY : 0);

        echo '<pre style="font:400 16px/1.4 monospace;background:#333;color:#aaa;border:1px solid #f00;margin:1px;padding:0.25em 0.5em;text-align:left;">';
        foreach ($this->logs as $log) {
            printf(
                "% 8.4fs % 8.4fs % 5dKB % 6dKB %s\n",
                $log[1] - $prevTm,
                $log[1] - $firstTm,
                ($log[2] - $prevMem) / 1024,
                ($log[2] - $firstMem) / 1024,
                $log[0]
            );

            $prevTm = $log[1];
            $prevMem = $log[2];
        }
        echo '</pre>';
    }
}
