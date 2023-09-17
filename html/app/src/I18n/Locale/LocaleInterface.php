<?php

namespace App\I18n\Locale;

interface LocaleInterface
{
    /** @return string */
    public function getLocale();

    /** @return string */
    public function getRegion();

    /** @return string */
    public function getLanguage();

    /** @return \Zend_Locale */
    public function getDriver();

    /** @return string */
    public function __toString();
}
