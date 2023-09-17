<?php

use App\Module\AbstractModule;
use App\Auth;

class Panel_Bootstrap extends AbstractModule
{
    /** @return string */
    public function getDir()
    {
        return __DIR__;
    }

    protected function _initAuth()
    {
        $config = [
            'roles' => [
                'anon' => [],
                'user',
                'admin' => 'user',
            ],
            'allow' => [
                '/panel/signin/' => ['anon'],
                '/panel/' => ['user'],
                '/panel/config/' => ['admin'],
            ],
        ];

        $auth = new Auth\Service();
        \Zend_Controller_Action_HelperBroker::addHelper(new Auth\ActionHelper($auth));
        \Zend_Controller_Front::getInstance()->registerPlugin(new Auth\ControllerPlugin($auth, $config));
    }

    protected function _initRoutes()
    {
        /** @see Zend_Application_Resource_Router */
        /** @see Zend_Controller_Router_Route_Static */
        /** @var Zend_Controller_Router_Rewrite $router */
        $router = $this->get('router');

        $router->addConfig(new Zend_Config([
            'panel' => [
                'route' => '/panel/:lang',
                'defaults' => [
                    'module' => 'panel',
                    'lang' => 'en',
                ],
                'reqs' => [
                    'lang' => '[a-z]{2}',
                ],
                'chains' => [
                    'index' => [
                        'route' => '/:controller',
                        'defaults' => [
                            'module' => 'panel',
                            'action' => 'index',
                        ],
                        'reqs' => [
                            'controller' => '[a-z]+',
                        ],
                    ],
                    'home' => [
                        'route' => '/',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'index',
                            'action' => 'index',
                        ],
                    ],
                    'profile' => [
                        'route' => '/profile',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'user',
                            'action' => 'profile',
                        ],
                    ],
                    'edit' => [
                        'route' => '/edit/:controller/:id',
                        'defaults' => [
                            'module' => 'panel',
                            'action' => 'edit',
                        ],
                        'reqs' => [
                            'id' => '\d+',
                            'controller' => '[a-z]+',
                        ],
                    ],
                    'menus' => [
                        'route' => '/menus',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'menu',
                            'action' => 'menus',
                        ],
                        'reqs' => [
                            'sid' => '[a-z]+',
                            'id' => '\d+',
                        ],
                    ],
                    'menu' => [
                        'route' => '/menu/:sid',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'menu',
                            'action' => 'tree',
                        ],
                        'reqs' => [
                            'sid' => '[a-z]+',
                        ],
                        'chains' => [
                            'node' => [
                                'route' => '/:type/:parent/:id',
                                'defaults' => [
                                    'action' => 'node',
                                    'controller' => 'menu',
                                ],
                                'reqs' => [
                                    'type' => '[a-z]+',
                                    'parent' => '\d+',
                                    'id' => '\d+',
                                ],
                            ],
                            'tree' => [
                                'route' => '/:parent',
                                'defaults' => [
                                    'action' => 'tree',
                                    'controller' => 'menu',
                                ],
                                'reqs' => [
                                    'parent' => '\d+',
                                ],
                            ],
                        ],
                    ],
                    'delete' => [
                        'route' => '/delete/:controller',
                        'defaults' => [
                            'module' => 'panel',
                            'action' => 'delete',
                        ],
                        'reqs' => [
                            'controller' => '[a-z]+',
                        ],
                    ],
                    'user-edit' => [
                        'route' => '/user/:id',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'user',
                            'action' => 'edit',
                        ],
                        'reqs' => [
                            'id' => '\d+',
                        ],
                    ],
                    'translations' => [
                        'route' => '/translations',
                        'defaults' => [
                            'module' => 'panel',
                            'controller' => 'config',
                            'action' => 'translations',
                        ],
                    ],
                ],
            ],
            'panel-signin' => [
                'route' => '/panel/signin',
                'defaults' => [
                    'module' => 'panel',
                    'controller' => 'guest',
                    'action' => 'signin',
                ],
            ],
            'panel-signout' => [
                'route' => '/panel/signout',
                'defaults' => [
                    'module' => 'panel',
                    'controller' => 'user',
                    'action' => 'signout',
                ],
            ],
        ]));
    }

    protected function _initMenu()
    {
        /** @var \App\Menu\MenuBuilder $mb */
        $mb = $this->get('menuBuilder');

        $mb->registerContainer('panel', new Panel\Menu\Container([
            [
                'label' => 'CMS panel',
                'route' => 'panel/home',
                'resetParams' => false,
                'pages' => [
                    [
                        'label' => 'Translations',
                        'route' => 'panel/translations',
                        'resetParams' => false,
                    ],
                    [
                        'label' => 'Menus',
                        'route' => 'panel/menus',
                        'resetParams' => false,
                    ],
                ],
            ],
            [
                'label' => 'Users',
                'route' => 'panel/index',
                'resetParams' => false,
                'controller' => 'user',
            ],
            [
                'label' => 'Uploads',
                'route' => 'panel/index',
                'resetParams' => false,
                'controller' => 'uploads',
            ],
        ]));
    }
}
