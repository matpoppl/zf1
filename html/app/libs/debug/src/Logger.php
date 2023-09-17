<?php
/*
 * File
 */

namespace matpoppl\Debug;

/*
 * Class
 */
class Logger
{
    /** @var array */
    private $logs = [];

    public function log($level, $msg)
    {
        $this->logs[] = [$level, $msg];
        return $this;
    }

    public function critical($msg)
    {
        $this->hasErrors = true;
        return $this->log(__FUNCTION__, $msg);
    }

    public function error($msg)
    {
        $this->hasErrors = true;
        return $this->log(__FUNCTION__, $msg);
    }

    public function warning($msg)
    {
        return $this->log(__FUNCTION__, $msg);
    }

    public function notice($msg)
    {
        return $this->log(__FUNCTION__, $msg);
    }

    public function debug($msg)
    {
        return $this->log(__FUNCTION__, $msg);
    }

    public function handleError($type, $msg, $file, $line, $context = null, $trace = null)
    {
        $this->error(self::getErrorType($type) . "({$type}) {$msg}\n{$file}:{$line}" . (null === $trace ? '' : "\n" . $trace));
    }

    /**
     *
     * @param \Exception $ex
     */
    public function handleException($ex)
    {
        $this->critical($ex->getCode() .'# '. $ex->getMessage() ."\n". $ex->getFile() .':'. $ex->getLine() ."\n". $ex->getTraceAsString());
    }

    /**
     *
     * @param \Exception $ex
     */
    public function print()
    {
        if (empty($this->logs)) {
            return;
        }

        echo '<pre style="font:400 16px/1.4 monospace;background:#333;color:#aaa;border:1px solid #f00;margin:1px;padding:0.25em 0.5em;text-align:left;">';
        foreach ($this->logs as $log) {
            printf(
                "[% 8s] %s\n",
                $log[0],
                str_replace("\n", "\n\t\t\t", $log[1])
            );
        }
        echo '</pre>';
    }

    /**
     *
     * @param int $type
     * @return string
     */
    public static function getErrorType($type)
    {
        static $types = null;

        if (null === $types) {
            $types = [];
            foreach (get_defined_constants() as $name => $val) {
                if ('E_' === strpos($name, 0, 2)) {
                    $types[$val] = $name;
                }
            }
        }

        return array_key_exists($type, $types) ? $types[$type] : 'UNKNOWN';
    }
}
