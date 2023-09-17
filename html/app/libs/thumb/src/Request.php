<?php

namespace matpoppl\Thumbnail;

class Request
{
    /** @var string */
    public $src;
    public $dest;

    /** @var int */
    public $width;
    public $height;

    /** @var string|null */
    public $format;
    /** @var float|null */
    public $quality;

    public function __construct($src, $dest, $width, $height, $format = null, $quality = null)
    {
        $this->src = $src;
        $this->dest = $dest;
        $this->width = $width;
        $this->height = $height;
        $this->format = $format;
        $this->quality = $quality;
    }

    /**
     *
     * @param array $options
     * @return Request
     */
    public static function create(array $options)
    {
        return new self(
            $options['src'],
            $options['dest'],
            $options['width'] ?? 0,
            $options['height'] ?? 0,
            $options['format'] ?? null,
            $options['quality'] ?? null
        );
    }
}
