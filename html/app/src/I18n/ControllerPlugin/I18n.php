<?php

namespace App\I18n\ControllerPlugin;

use Zend_Controller_Request_Abstract as Request;
use App\Controller\Plugin\AbstractPlugin;
use App\I18n\Locale\LocaleManager;

class I18n extends AbstractPlugin
{
    /** @var LocaleManager */
    private $mgr;

    public function __construct(LocaleManager $mgr)
    {
        $this->mgr = $mgr;
    }

    public function routeShutdown(Request $req)
    {
        $lang = $req->getParam('lang');

        if ($lang) {
            $locale = strtolower($lang) . '_' . strtoupper($lang);
            $this->mgr->setLocale($locale);
        } else {
            $req->setParam('lang', $this->mgr->getLocale()->getLanguage());
            $this->get('router')->setGlobalParam('lang', $this->mgr->getLocale()->getLanguage());
        }
    }
}
