<?php

namespace App\Site;

use App\I18n\Locale\Locale;

class Site implements SiteInterface
{
    private $options;

    private $locales = null;
    
    public function __construct(array $options)
    {
        $this->options = $options;
    }
    
    public function getId()
    {
        return 1;
    }
    
    /** @return \App\I18n\Locale\Locale[] */
    public function getLocales()
    {
        if (null === $this->locales) {
            $this->locales = [];

            foreach ($this->options['locales'] as $locale) {
                $locale = \Zend_Locale::findLocale($locale);
                $this->locales[$locale] = new Locale(new \Zend_Locale($locale));
            }
        }

        return $this->locales;
    }
}
