<?php

namespace App\I18n\Translator;

class Translator implements TranslatorInterface
{
    /** @var \Zend_Translate */
    private $driver;

    public function __construct(\Zend_Translate $driver)
    {
        $this->driver = $driver;
    }

    public function setLocale($locale)
    {
        $this->driver->getAdapter()->setLocale($locale);
        return $this;
    }

    public function setDomain($domain)
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function translatePlural($singular, $plural, $count, $domain = null)
    {
        return $this->driver->getAdapter()->plural($singular, $plural, $count);
    }

    public function translate($msg, $domain = null)
    {
        return $this->driver->getAdapter()->translate($msg);
    }
}
