<?php

namespace App\I18n\Translator;

interface TranslatorInterface
{
    /**
     *
     * @param string $locale
     * @return TranslatorInterface
     */
    public function setLocale($locale);

    /**
     *
     * @param string $domain
     * @return TranslatorInterface
     */
    public function setDomain($domain);

    /**
     *
     * @param string $msg
     * @param string $domain
     * @return string
     */
    public function translate($msg, $domain = null);

    /**
     *
     * @param string $singular
     * @param string $plural
     * @param int $count
     * @param string $domain
     * @return string
     */
    public function translatePlural($singular, $plural, $count, $domain = null);
}
