<?php

namespace App\Route;

use App\I18n\Transliterator\TransliteratorInterface;

class PathFormatter
{
    /** @var TransliteratorInterface */
    private $translit;

    public function __construct(TransliteratorInterface $translit)
    {
        $this->translit = $translit;
    }

    /**
     *
     * @param string|array $pathParts
     * @return string
     */
    public function format($pathParts)
    {
        return $this->translit->transliterate(strtolower(is_array($pathParts) ? implode('/', $pathParts) : $pathParts));
    }
}
