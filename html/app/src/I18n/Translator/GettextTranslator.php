<?php

namespace App\I18n\Translator;

class GettextTranslator implements TranslatorInterface
{
    public function registerDomain($domain, $dirname)
    {
        bindtextdomain($domain, $dirname);
        return $this;
    }

    public function setLocale($locale)
    {
        putenv('LC_ALL=' . $locale);
        setlocale(LC_ALL, $locale);
    }

    public function setDomain($domain)
    {
        textdomain($domain);
        return $this;
    }

    public function translate($message, $domain = null)
    {
        return (null === $domain) ? gettext($message) : dgettext($domain, $message);
    }

    public function translatePlural($singular, $plural, $count, $domain = null)
    {
        return  dngettext($domain, $singular, $plural, $count);
    }
}
