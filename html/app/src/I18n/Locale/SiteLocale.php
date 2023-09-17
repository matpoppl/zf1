<?php

namespace App\I18n\Locale;

class SiteLocale extends Locale implements SiteLocaleInterface
{
    /** @var int */
    private $id;

    public function __construct($id, LocaleInterface $locale)
    {
        $this->id = $id;
        parent::__construct($locale->getDriver());
    }

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    /** @return string */
    public function getCharset()
    {
        return 'UTF-8';
    }
}
