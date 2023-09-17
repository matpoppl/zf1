<?php

namespace App\I18n\Locale;

class Locale implements LocaleInterface
{
    /** @var \Zend_Locale */
    private $driver;

    public function __construct(\Zend_Locale $driver)
    {
        $this->driver = $driver;
    }

    /** @return string */
    public function getLocale()
    {
        return $this->driver->toString();
    }

    /** @return string */
    public function getRegion()
    {
        return $this->driver->getRegion();
    }

    /** @return string */
    public function getLanguage()
    {
        return $this->driver->getLanguage();
    }

    /** @return \Zend_Locale */
    public function getDriver()
    {
        return $this->driver;
    }

    /** @return string */
    public function __toString()
    {
        return $this->getLocale();
    }
}
