<?php

namespace matpoppl\Thumbnail;

class Result
{
    /** @var string */
    public $path;
    /** @var int */
    public $width;
    /** @var int */
    public $height;

    public function __construct($path, $width, $height)
    {
        $this->path = $path;
        $this->width = $width;
        $this->height = $height;
    }

    public function __toString()
    {
        return $this->path;
    }
}
