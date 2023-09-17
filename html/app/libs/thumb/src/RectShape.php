<?php

namespace matpoppl\Thumbnail;

class RectShape
{
    public $width = 0;
    public $height = 0;
    public $x = 0;
    public $y = 0;
    public $angle = 0;

    public function __construct(int $w, int $h, int $x = null, int $y = null, float $a = null)
    {
        $this->width = $w;
        $this->height = $h;
        $this->x = $x ?: 0;
        $this->y = $y ?: 0;
        $this->angle = $a ?: 0;
    }

    public function resize(int $destW, int $destH)
    {
        if ($destH / $destW > $this->height / $this->width) {
            $w = $destW;
            $h = ($destW / $this->width) * $this->height;
        } else {
            $h = $destH;
            $w = ($destH / $this->height) * $this->width;
        }

        $x = ($destW - $w) / 2;
        $y = ($destH - $h) / 2;

        return new self($w, $h, $x, $y);
    }
}
