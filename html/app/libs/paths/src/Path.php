<?php

namespace matpoppl\Paths;

class Path
{
    /** @var string */
    private $path;

    public function __construct($path)
    {
        $this->path = rtrim($path, '\\/');
    }

    public function append($path)
    {
        return new self($this->path . '/' . $path);
    }

    public function relative($path)
    {
        $len = strlen($this->path);
        $path = substr($path, $len);
        return new self($path);
    }

    public function __toString()
    {
        return $this->path;
    }

    /** @return \matpoppl\Paths\PathInfo */
    public function asPathInfo()
    {
        return new PathInfo($this->path);
    }
}
