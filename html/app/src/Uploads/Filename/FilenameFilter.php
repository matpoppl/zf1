<?php

namespace App\Uploads\Filename;

class FilenameFilter implements \Zend_Filter_Interface
{
    /** @var Transliterator */
    private $translit = null;

    public function getTransliterator()
    {
        if (null === $this->translit) {
            $this->translit = new Transliterator();
        }

        return $this->translit;
    }

    public function getNewFilename($value)
    {
        $parts = pathinfo($value);

        $dir = $parts['dirname'] ?? '';
        $filename = $parts['filename'] ?? '';
        $ext = $parts['extension'] ?? '';

        $filename = $this->getTransliterator()->trans($filename);
        $ext = $this->getTransliterator()->trans($ext);

        $filename = strtolower($filename);
        $ext = strtolower($ext);

        $filename = preg_replace('#[^a-z0-9]+#', '-', $filename);
        $ext = preg_replace('#[^a-z0-9]+#', '-', $ext);

        $filename = trim($filename, '-');
        $ext = trim($ext, '-');

        return $dir . '/' . $filename . '.' . $ext;
    }

    public function filter($filename)
    {
        $newFilename = $this->getNewFilename($filename);

        if (! rename($filename, $newFilename)) {
            throw new \RuntimeException('File rename error');
        }

        return $newFilename;
    }
}
