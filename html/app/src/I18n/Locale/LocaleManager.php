<?php

namespace App\I18n\Locale;

use Zend_Locale;
use App\I18n;
use matpoppl\ServiceManager\ContainerInterface;

class LocaleManager
{
    /** @var LocaleInterface */
    private $locale;
    /** @var array */
    private $options;

    public function __construct(LocaleInterface $locale, array $options = null)
    {
        $this->locale = $locale;
        $this->options = $options ?: [];
    }

    public function register(ContainerInterface $app)
    {
        $app->get('frontController')->registerPlugin(new I18n\ControllerPlugin\I18n($this));
        $app->get('view')->registerHelper(new I18n\ViewHelper\I18n($this), 'i18n');
        return $this;
    }

    /**
     *
     * @param string|\Zend_Locale|LocaleInterface $locale
     * @throws \InvalidArgumentException
     * @return \App\I18n\Locale\LocaleManager
     */
    public function setLocale($locale)
    {
        if (is_string($locale)) {
            $locale = Zend_Locale::findLocale($locale);
            $locale = new Zend_Locale($locale);
        }

        if ($locale instanceof Zend_Locale) {
            $locale = new Locale($locale);
        }

        if (! ($locale instanceof LocaleInterface)) {
            throw new \InvalidArgumentException('Unsupported locale type');
        }

        $this->locale = $locale;

        return $this;
    }

    /** @return \App\I18n\Locale\LocaleInterface */
    public function getLocale()
    {
        return $this->locale;
    }
}
