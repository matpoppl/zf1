<?php

namespace App\I18n\Transliterator;

interface TransliteratorInterface
{
    /**
     *
     * @param string $value
     * @return string
     */
    public function transliterate($value);
}
