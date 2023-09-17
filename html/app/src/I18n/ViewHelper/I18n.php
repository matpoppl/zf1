<?php

namespace App\I18n\ViewHelper;

use App\View\Helper\AbstractHelper;
use App\I18n\Locale\LocaleManager;

class I18n extends AbstractHelper
{
    /** @var LocaleManager */
    private $mgr;

    public function __construct(LocaleManager $mgr)
    {
        $this->mgr = $mgr;
    }

    /** @return \App\I18n\ViewHelper\I18n */
    public function i18n()
    {
        return $this;
    }

    public function direct()
    {
        return $this;
    }

    /** @return \App\I18n\Locale\Locale */
    public function getLocale()
    {
        return $this->mgr->getLocale();
    }
}
