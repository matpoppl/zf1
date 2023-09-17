<?php

namespace App\Site;

interface SiteInterface
{
    /** @return int */
    public function getId();
    
    /** @return \App\I18n\Locale\Locale[] */
    public function getLocales();
}
