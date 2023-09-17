<?php

namespace matpoppl\Paths;

/**
 * @property string basename
 */
class PathInfo
{
    /** @var Path */
    public $dirname;

    /** @var string */
    public $prefix;

    /** @var string */
    public $filename;

    /** @var string */
    public $suffix;

    /** @var string */
    public $extension;

    public function __construct($pathname)
    {
        $parts = pathinfo($pathname);
        $this->dirname = new Path($parts['dirname']);
        $this->filename = $parts['filename'] ?? null;
        $this->extension = $parts['extension'] ?? null;
    }

    public function __set($name, $value)
    {
        if ('basename' === $name) {
            $parts = pathinfo($value);

            $this->filename = $parts['filename'] ?? null;
            $this->extension = $parts['extension'] ?? null;
        }
    }

    public function __get($name)
    {
        if ('basename' !== $name) {
            throw new \DomainException('Unsupported property `' . $name . '`');
        }

        return $this->prefix . $this->filename . $this->suffix . rtrim('.' . $this->extension, '.');
    }

    public function __toString()
    {
        return $this->dirname->append($this->basename)->__toString();
    }
}
