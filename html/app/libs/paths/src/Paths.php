<?php

namespace matpoppl\Paths;

class Paths
{
    /** @var string[]|Path[] */
    private $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     *
     * @param string $path
     * @throws \UnexpectedValueException
     * @return \matpoppl\Paths\Path
     */
    public function get($path)
    {
        $pos = strpos($path, ':');

        if (false === $pos) {
            $prefix = $path;
        } else {
            $prefix = substr($path, 0, $pos);
        }

        if (! array_key_exists($prefix, $this->paths)) {
            throw new \UnexpectedValueException("Prefix {$prefix} not found");
        }

        if (is_string($this->paths[$prefix])) {
            $this->paths[$prefix] = new Path($this->paths[$prefix]);
        }

        if (false === $pos) {
            return $this->paths[$prefix];
        }

        $path = ltrim(substr($path, $pos + 1), '//');
        return $this->paths[$prefix]->append(self::join($path));
    }

    public function asRelativeTo($path)
    {
        $relTo = $this->get($path);

        $ret = clone $this;

        foreach ($ret->paths as $key => $path) {
            $ret->paths[$key] = $relTo->relative($path);
        }

        return $ret;
    }

    /**
     *
     * @param string $path
     * @return string
     */
    public static function normalize($path)
    {
        return preg_replace('#[\\/]+#', '/', $path);
    }

    /**
     *
     * @param string $arg0
     * @param string $arg1
     * @return string
     */
    public static function join(...$args)
    {
        $ret = '';

        foreach ($args as $arg) {
            $arg = self::normalize($arg);
            $first = '/' === substr($arg, 0, 1) ? '/' : '';
            $arg = trim($arg, '/');

            $parts = [];

            foreach (explode('/', $arg) as $part) {
                switch ($part) {
                    case '.':
                        break;
                    case '..':
                        array_pop($parts);
                        break;
                    default:
                        $parts[] = $part;
                        break;
                }
            }

            $ret .= $first . implode('/', $parts);
        }

        return $ret;
    }
}
