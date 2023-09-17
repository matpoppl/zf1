<?php

use App\Crypto\Hasher;
use App\EntityManager;
use App\Form\Builder\FormBuilderService;
use App\I18n\Locale;
use App\Menu;
use App\Menu\Entity as MenuEntity;
use App\Page\Entity\PageEntity;
use App\Page\Entity\PageRepository;
use App\Page\Metadata\Entity\PageMetaEntity;
use App\Page\Metadata\Entity\PageMetaRepository;
use App\Site;
use App\Uploads\Entity\AttachmentEntity;
use App\Uploads\Entity\AttachmentRepository;
use matpoppl\Paths;
use matpoppl\ServiceManager\Factory\InvokableFactory;
use App\User\Entity as UserEntity;
use App\Route\Entity as RouteEntity;

$localConfigFile = __DIR__ . '/zend-local.php';
$dirApp = dirname(__DIR__) . '/';
$docRoot = dirname($dirApp) . '/';

return [
    /** @see Zend_Application_Bootstrap_Bootstrap::getResourceLoader() */
    'resourceLoader' => new App\Loader\ResourceLoader(),
    //'pluginloader' => ???,

    'config' => is_file($localConfigFile) ? $localConfigFile : null,
    'appNamespace' => 'App_',

    'phpSettings' => [],
    //'autoloaderNamespaces' => [],

    'bootstrap' => [
        'class' => 'App\\Bootstrap',
        'path' => $dirApp . 'src/Bootstrap.php',
    ],

    'site_manager' => [
        'default_site' => 'default',
        'sites' => [
            'default' => [
                'locales' => ['pl_PL', 'en_GB', 'de_DE'],
            ],
        ],
    ],

    'i18n' => [
    ],

    'entity_manager' => [
        /*
        'abstract_factories' => [
            EntityManager\AbstractRepositoryFactory::class,
        ],
        */

        'aliases' => [
            'attachment' => AttachmentEntity::class,
            'attachments' => AttachmentEntity::class,
            'pagemeta' => PageMetaEntity::class,
            'pagemetas' => PageMetaEntity::class,
            'pages' => PageEntity::class,
            'page' => PageEntity::class,
            'menu' => MenuEntity\MenuEntity::class,
            'menus' => MenuEntity\MenuEntity::class,
            'menu_links' => MenuEntity\MenuLinkEntity::class,
        ],

        'repositories' => [
            AttachmentEntity::class => [
                'type' => AttachmentRepository::class,
                'table' => 'attachments',
            ],
            MenuEntity\MenuEntity::class => [
                'type' => MenuEntity\MenuRepository::class,
                'table' => [
                    'name' => 'menus',
                    //'primary' => '', // string or array of primary key(s).
                    //'rowClass' => '', // row class name.
                    //'rowsetClass' => '', // rowset class name.
                    //'referenceMap' => '', // array structure to declare relationship to parent tables.
                    //'dependentTables' => '', // array of child tables.
                    //'metadataCache' => '', // cache for information from adapter describeTable().
                ],
            ],
            MenuEntity\MenuLinkEntity::class => [
                'type' => MenuEntity\MenuLinkRepository::class,
                'table' => [
                    'name' => 'menu_links',
                    //'primary' => '', // string or array of primary key(s).
                    //'rowClass' => '', // row class name.
                    //'rowsetClass' => '', // rowset class name.
                    //'referenceMap' => '', // array structure to declare relationship to parent tables.
                    //'dependentTables' => '', // array of child tables.
                    //'metadataCache' => '', // cache for information from adapter describeTable().
                ],
            ],
            PageEntity::class => [
                'type' => PageRepository::class,
                'table' => 'pages',
            ],
            PageMetaEntity::class => [
                'type' => PageMetaRepository::class,
                'table' => 'pagemetas',
            ],
            RouteEntity\RouteEntity::class => [
                'type' => RouteEntity\RouteRepository::class,
                'table' => 'routes',
            ],
            UserEntity\UserEntity::class => [
                'type' => UserEntity\UserRepository::class,
                'table' => [
                    'type' => UserEntity\UserDbTable::class,
                ],
            ],
        ],
    ],

    'paths' => [
        'doc-root' => $docRoot,
        'tmp' => $dirApp . 'var/tmp',
        'cache' => $dirApp . 'var/cache',
        'uploads' => $docRoot . 'static/uploads',
        'cache-uploads' => $docRoot . 'static/var/uploads',
    ],

    //'pluginPaths' => [''],
    'resources' => [
        /** @see Zend_Application_Resource_Modules */
        'modules' => [],
        /** @see Zend_Application_Resource_Layout */
        'layout' => [],
        /** @see Zend_Application_Resource_Locale */
        'locale' => [
            'default' => 'pl_PL',
            'force' => true,
        ],
        /** @see Zend_Application_Resource_Db */
        'db' => [],
        /** @see Zend_Application_Resource_Frontcontroller */
        'frontController' => [
            'defaultModule' => 'page',
            'defaultControllerName' => 'index',
            'defaultAction' => 'index',
            'controllerdirectory' => 'controllers',
            'moduleDirectory' => $dirApp . 'modules',
            'throwExceptions' => false,
            'returnResponse' => false,
            'params' => [
                'prefixDefaultModule' => true,
                'disableOutputBuffering' => true,
                'noViewRenderer' => false,
                'noErrorHandler' => false,
            ],
            'plugins' => [
                \App\Controller\Plugin\Profiler::class,
                \App\Controller\Plugin\Layout::class,
                \App\Controller\Plugin\MetaRoute::class,
            ],
        ],
        /** @see Zend_Application_Resource_Cachemanager */
        'cacheManager' => [
            'default' => [
                /** @see Zend_Cache_Core */
                'frontend' => [
                    'name' => 'Core',
                    'options' => [
                        'lifetime' => 86400,
                    ],
                ],
                /** @see Zend_Cache_Backend_File */
                'backend' => [
                    'name' => 'File',
                    'options' => [
                        'cache_dir' => $dirApp . 'var/cache',
                    ],
                ],
            ],
        ],
        /** @see Zend_Application_Resource_Log */
        'log' => [
            'timestampFormat' => 'Y-m-d H:i:s',
            'default' => [
                /** @see Zend_Log_Writer_Stream */
                'writerName' => 'Stream',
                'writerParams' => [
                    'url' => $dirApp . 'var/logs/zend.log',
                ],
            ],
        ],
        /** @see Zend_Application_Resource_Translate */
        'translate' => [
            /** @see Zend_Translate_Adapter_Array */
            'adapter' => 'Array',
            'content' => $dirApp . 'messages',
            /** @see pl_PL.php */
            'scan' => Zend_Translate::LOCALE_FILENAME,
            /** @see Zend_Locale */
            'locale' => 'pl_PL',
            'cache' => 'default',
            'logUntranslated' => false,
            /** @see Zend_Log */
            'log' => [
                'translate' => [
                    /** @see Zend_Log_Writer_Stream */
                    'writerName' => 'Stream',
                    'writerParams' => [
                        'url' => $dirApp . 'var/logs/zend-translate.log',
                    ],
                ],
            ],
        ],
        /** @see Zend_Application_Resource_Router */
        'router' => [
            'chainNameSeparator' => '/',
        ],
        /** @see Zend_Application_Resource_Session */
        'session' => [
            'save_path'                 => $dirApp . 'var/sessions',
            'name'                      => 'App',
            'gc_maxlifetime'            => 86400,
            'cookie_lifetime'           => 86400,
            'cookie_path'               => '/',
            'cookie_domain'             => '.zf1.pop-pc.lan',
            'cookie_secure'             => false,
            'cookie_httponly'           => true,
            //'cookie_samesite' => 'Strict',
            'use_cookies'               => true,
            'use_only_cookies'          => true,
            //'referer_check'             => true,

            'strict' => false,
            'throw_startup_exceptions' => true,
        ],
        /** @see Zend_Application_Resource_Navigation */
        'navigation' => [],
        /** @see Zend_Application_Resource_View */
        'view' => [
            'doctype' => 'HTML5',
            'charset' => 'UTF-8',
        ],
    ],
    'service_manager' => [
        'aliases' => [
            /*
            'cachemanager' => 'Cachemanager',
            'cacheManager' => 'Cachemanager',
            'db' => 'Db',
            'dojo' => 'Dojo',
            'exception' => 'Exception',
            'FrontController' => 'Frontcontroller',
            'frontController' => 'Frontcontroller',
            'frontcontroller' => 'Frontcontroller',
            'layout' => 'Layout',
            'locale' => 'Locale',
            'log' => 'Log',
            'mail' => 'Mail',
            'modules' => 'Modules',
            'multidb' => 'Multidb',
            'navigation' => 'Navigation',
            'resource' => 'Resource',
            'router' => 'Router',
            'session' => 'Session',
            'translate' => 'Translate',
            'useragent' => 'Useragent',
            'view' => 'View',
            */

            'EntityManager' => EntityManager\EntityManager::class,
            'formBuilder' => FormBuilderService::class,
            'paths' => Paths\Paths::class,
            'menuBuilder' => Menu\MenuBuilder::class,
            'sites' => Site\SiteManager::class,

            Hasher\PasswordHasherInterface::class => Hasher\PasswordHasher::class,

            //'auth' => \App\Auth\Application\Resource::class,

            'i18n' => Locale\LocaleManager::class,
        ],
        'factories' => [

            //\App\Auth\Application\Resource::class => \App\Auth\Application\ResourceFactory::class,

            EntityManager\EntityManager::class => EntityManager\EntityMananagerFactory::class,

            Hasher\PasswordHasher::class => InvokableFactory::class,
            FormBuilderService::class => InvokableFactory::class,
            Paths\Paths::class => Paths\PathsFactory::class,

            Menu\MenuBuilder::class => Menu\MenuBuilderFactory::class,

            Locale\LocaleManager::class => Locale\LocaleManagerFactory::class,
            Site\SiteManager::class => Site\SiteManagerFactory::class,

            /*
            'Cachemanager' => ResourceFactory::class,
            'Db' => ResourceFactory::class,
            'Dojo' => ResourceFactory::class,
            'Exception' => ResourceFactory::class,
            'Frontcontroller' => ResourceFactory::class,
            'Layout' => ResourceFactory::class,
            'Locale' => ResourceFactory::class,
            'Log' => ResourceFactory::class,
            'Mail' => ResourceFactory::class,
            'Modules' => ResourceFactory::class,
            'Multidb' => ResourceFactory::class,
            'Navigation' => ResourceFactory::class,
            'Resource' => ResourceFactory::class,
            'ResourceAbstract' => ResourceFactory::class,
            'Router' => ResourceFactory::class,
            'Session' => ResourceFactory::class,
            'Translate' => ResourceFactory::class,
            'Useragent' => ResourceFactory::class,
            'View' => ResourceFactory::class,
            */
/*
            'ActionStack' => ActionHelperFactory::class,
            'AjaxContext' => ActionHelperFactory::class,
            'AutoCompleteDojo' => ActionHelperFactory::class,
            'AutoCompleteScriptaculous' => ActionHelperFactory::class,
            'Cache' => ActionHelperFactory::class,
            'ContextSwitch' => ActionHelperFactory::class,
            'FlashMessenger' => ActionHelperFactory::class,
            'Json' => ActionHelperFactory::class,
            'Redirector' => ActionHelperFactory::class,
            'Url' => ActionHelperFactory::class,
            'ViewRenderer' => ActionHelperFactory::class,
*/
        ],
    ],
];
