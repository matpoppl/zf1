<?php

namespace App\I18n\Locale;

interface SiteLocaleInterface extends LocaleInterface
{
    /** @return int */
    public function getId();

    /** @return string */
    public function getCharset();
}
