<?php

namespace App\I18n\Transliterator;

class AsciiTransliterator implements TransliteratorInterface
{
    /** @var string */
    private $sourceLocale;
    /** @var string */
    private $sourceCharset;
    /** @var bool */
    private $fallbackIgnore = false;

    /**
     *
     * @param string $sourceLocale
     */
    public function __construct($sourceLocale, $sourceCharset)
    {
        $this->sourceLocale = (string) $sourceLocale;
        $this->sourceCharset = (string) $sourceCharset;
    }

    public function transliterate($input)
    {
        setlocale(LC_CTYPE, $this->sourceLocale);
        $value = iconv($this->sourceCharset, 'US-ASCII//TRANSLIT', $input);

        if (false === $value && ! $this->fallbackIgnore) {
            throw new \UnexpectedValueException('Strict transliteration failed');
        }

        return $value ?: iconv($this->sourceCharset, 'US-ASCII//IGNORE', $input);
    }
}
