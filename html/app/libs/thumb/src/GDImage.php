<?php

namespace matpoppl\Thumbnail;

use RuntimeException;

class GDImage
{
    /** @var resource */
    private $resource;
    /** @var int IMAGETYPE_XXX */
    private $type;
    /** @var RectShape */
    private $shape;

    private static $FORMAT_QUALITY_MAX = [
        \IMAGETYPE_PNG => 9,
        \IMAGETYPE_JPEG => 100,
        \IMAGETYPE_WEBP => 100,
    ];

    public function __construct($resource, int $type, RectShape $shape)
    {
        if (! is_resource($resource)) {
            throw new RuntimeException('Invalid GD resource');
        }

        $this->resource = $resource;
        $this->type = $type;
        $this->shape = $shape;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if (null !== $this->resource) {
            imagedestroy($this->resource);
        }

        $this->resource = null;
    }

    /** @return int IMAGETYPE_XXX */
    public function getType()
    {
        return $this->type;
    }

    public function setType(int $type)
    {
        $this->type = $type;
        return $this;
    }

    /** @return RectShape */
    public function getShape()
    {
        return $this->shape;
    }

    public function resize(int $destW, int $destH)
    {
        $dstShape = new RectShape($destW, $destH);
        $srcShape = $dstShape->resize($this->shape->width, $this->shape->height);

        $new = imagecreatetruecolor($dstShape->width, $dstShape->height);
        $ok = imagecopyresampled($new, $this->resource, $dstShape->x, $dstShape->y, $srcShape->x, $srcShape->y, $dstShape->width, $dstShape->height, $srcShape->width, $srcShape->height);

        if (! $ok) {
            throw new RuntimeException('Image copy failed');
        }

        $ret = new self($new, $this->type, new RectShape($dstShape->width, $dstShape->height));

        if ($this->shape->angle > 0) {
            return $ret->rotate($this->shape->angle - 360);
        }

        return $ret;
    }

    public function rotate(int $angle)
    {
        $bg = imagecolorat($this->resource, 0, 0);
        $new = imagerotate($this->resource, $angle, $bg);

        if (! is_resource($new)) {
            throw new RuntimeException('Image rotate failed');
        }

        return new self($new, $this->type, new RectShape($this->shape->h, $this->shape->w));
    }

    public function output(string $pathname = null, float $quality = null)
    {
        $mime = image_type_to_mime_type($this->type);
        $fn = 'image' . substr($mime, 6);

        if (! function_exists($fn)) {
            throw new RuntimeException('Unsupported GD image create function `'.$mime.'`');
        }

        if (null === $pathname) {
            header('Content-Type: ' . $mime);
        }

        if (null !== $quality && array_key_exists($this->type, self::$FORMAT_QUALITY_MAX)) {
            $quality = self::$FORMAT_QUALITY_MAX[$this->type] * $quality;
            $ok = $fn($this->resource, $pathname, $quality);
        } else {
            $ok = $fn($this->resource, $pathname);
        }

        if (! $ok) {
            throw new RuntimeException('GD output failed `'.$mime.'`');
        }

        return $this;
    }

    /**
     *
     * @param string $pathname
     * @throws RuntimeException
     * @return GDImage
     */
    public static function openFile(string $pathname)
    {
        $info = getimagesize($pathname);

        $mime = $info['mime'];
        $fn = 'imagecreatefrom' . substr($mime, 6);

        if (! function_exists($fn)) {
            throw new RuntimeException('Unsupported GD image create function `'.$mime.'`');
        }

        $angle = null;

        $exif = function_exists('exif_read_data') ? @exif_read_data($pathname) : null;

        if (is_array($exif) && array_key_exists('Orientation', $exif)) {
            switch ($exif['Orientation']) {
                case 3:
                    $angle = 180;
                    break;
                case 8:
                    $angle = 90;
                    break;
                case 6:
                    $angle = 270;
                    break;
            }
        }

        $resource = $fn($pathname);

        return new self($resource, $info[2], new RectShape($info[0], $info[1], null, null, $angle));
    }
}
