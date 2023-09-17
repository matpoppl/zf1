<?php

use App\Module\AbstractModule;

class Page_Bootstrap extends AbstractModule
{
    /** @return string */
    public function getDir()
    {
        return __DIR__;
    }

    protected function _initRoutes()
    {
        /** @see Zend_Application_Resource_Router */
        /** @see Zend_Controller_Router_Route_Static */
        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $this->get('router');

        $router->addConfig(new Zend_Config([
            'page/home' => [
                'route' => '/',
                'defaults' => [
                    'module' => 'page',
                    'controller' => 'index',
                    'action' => 'index',
                ],
            ],
            'page/contact' => [
                'route' => 'kontakt',
                'defaults' => [
                    'module' => 'page',
                    'controller' => 'contact',
                    'action' => 'contact',
                ],
            ],
            'page/locale/change' => [
                //'type' => Zend_Controller_Router_Route_Regex::class,
                'route' => '/change-lang/:locale',
                'defaults' => [
                    'module' => 'page',
                    'controller' => 'index',
                    'action' => 'locale-change',
                ],
                'reqs' => [
                    'locale' => '^[a-z]{2}(_[A-Z]{2})?$',
                ],
            ],
        ]));
    }


    protected function _initMenu()
    {
        /** @var \App\Menu\MenuBuilder $mb */
        $mb = $this->get('menuBuilder');

        $mb->registerContainer('page', new Panel\Menu\Container([
            [
                'route' => 'page/home',
                'label' => 'Home page',
            ], [
                'route' => 'page/contact',
                'label' => 'Contact page',
            ], [
                'uri' => '/kontakt.html',
                'label' => 'Kontakt',
            ],
        ]));
    }
}
