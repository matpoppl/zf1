<?php

namespace App\Controller\Action\Helper;

class Url2 extends AbstractHelper
{
    public function getName()
    {
        return 'Url2';
    }

    public function direct()
    {
        return $this;
    }

    public function getUrlSite()
    {
        $req = $this->getRequest();
        return $req->getScheme() . '://' . $req->getHttpHost() . rtrim($this->getFrontController()->getBaseUrl(), '/') . '/';
    }

    public function getUrlReferer($safe = true)
    {
        $ref = $this->getRequest()->getHeader('Referer');

        if (! $safe) {
            return $ref;
        }

        $siteUrl = $this->getUrlSite();

        return (0 === strpos($ref, $siteUrl)) ? $ref : $siteUrl;
    }
}
