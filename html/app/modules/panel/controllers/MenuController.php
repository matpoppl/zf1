<?php

use Panel\Controller\AbstractController;
use Panel\Menu\EditType;
use Panel\Menu\MenuType;
use matpoppl\Hydrator\ClassMethodsHydrator;
use App\Menu\Entity\MenuEntity;
use App\Menu\Entity\MenuLinkEntity;
use App\EntityManager\Hydrator\NestedHydrator;
use App\Page\Metadata\Entity\PageMetaEntity;
use App\Route\Entity\RouteEntity;
use App\Route\RouteGenerator;

class Panel_MenuController extends AbstractController
{
    public function nodeAction()
    {
        $site = $this->getSite();
        $locale = $this->getSiteLocale();

        $req = $this->getRequest();
        $em = $this->getEntityManager();

        /** @var MenuEntity $menu */
        $menu = $em->findBySID(MenuEntity::class, $req->getParam('sid'));

        /** @var MenuLinkEntity $menuLink */
        $menuLink = $em->findOrCreate(MenuLinkEntity::class, $req->getParam('id'));

        if ($menuLink->isNewRecord()) {
            $menuLink->setMenu($menu->getId());
            $menuLink->setParent($req->getParam('parent'));
            //$menuLink->setLang( $req->getParam('lang') );
        }

        switch ($req->getParam('type')) {
            case 'page':
                /** @var \App\Page\Entity\PageEntity $entity */
                $entity = $em->findOrCreate($req->getParam('type'), $req->getParam('id'));
                /** @var PageMetaEntity $meta */
                /** @see \App\Page\Metadata\Entity\PageMetaRepository::findByEntityOrCreate() */
                $meta = $em->findByEntityOrCreate(PageMetaEntity::class, $entity, $locale);
                break;
            default:
                throw new \DomainException('Unsupported entity type');
        }

        $formData = (object)[
            'page' => $entity,
            'link' => $menuLink,
            'meta' => $meta,
        ];
        
        /** @var \App\Form\AbstractForm $form */
        $form = $this->getFormBuilder()->formBuilder(MenuType::class, $formData, [
            'hydrator' => new NestedHydrator([
                'page' => new ClassMethodsHydrator(),
                'link' => new ClassMethodsHydrator(),
                'meta' => new ClassMethodsHydrator(),
            ]),
            'form_options' => [
                'method' => 'post',
            ]
        ])->getForm();

        if ($req->isPost()) {
            if ($form->isValid($req->getPost())) {
                $em->save($entity);

                $meta->bindEntity($entity, $locale);

                $em->save($meta);

                if (empty($menuLink->getLabel())) {
                    $menuLink->setLabel($entity->getName());
                }

                if (empty($menuLink->getUrl())) {
                    $routeGen = new RouteGenerator($em->getRepository(RouteEntity::class));
                    $menuLink->setUrl($routeGen->generateUniqueFrom($site->getId(), $locale, [$entity->getName()]));
                }

                if ($menuLink->getWeight() < 1) {
                    $menuLink->setWeight(1 + $em->getRepository(MenuLinkEntity::class)->getMaxWeight($menu->getId()));
                }
                
                /*
                 if (! $menuLink->hasAbsoluteUrl()) {
                 $route = $routes->findByUrl($menuLink->getUrl());
                 }
                 */
                
                /** @var RouteEntity $route */
                $route = $em->fetchBySiteUrl(RouteEntity::class, $site->getId(), $menuLink->getUrl()) ?: $em->getRepository(RouteEntity::class)->createEntity();

                $route->bindTargetEntity($entity)
                ->bindLocale($locale)
                ->bindSite($site)
                ->bindMenuLink($menuLink);
                
                $em->save($route);

                $menuLink->bindRoute($route);
                $em->save($menuLink);

                $this->_helper->log->info("MenuNode `" . $menuLink->getId() . "` saved");

                $url = $this->_helper->url->url(['parent' => $menuLink->getParent(), 'id' => $menuLink->getId(), 'type' => 'page'], 'panel/menu/node');

                $this->_helper->flashMessenger('saved', 'success');
                return $this->redirect($url);
            }
        }


        /** @var \App\Menu\MenuBuilder $mb */
        $mb = $this->get('menuBuilder');

        /** @var \Zend_Navigation_Page_Mvc $navPage */
        $mb->getContainer()->findBy('route', 'panel/menus')->addPage([
            'label' => 'Tree',
            'route' => 'panel/menu/tree',
            'resetParams' => false,
            //'visible' => false,
            'params' => [
                'parent' => 0,
            ],
            'pages' => [
                [
                    'label' => 'Node',
                    'route' => 'panel/menu/node',
                    'resetParams' => false,
                    'params' => [
                        'parent' => 0,
                        'id' => 0,
                        'type' => 'page',
                        'lang' => 'pl',
                    ],
                ],
            ],
        ]);

        return $this->render2('menu/node.phtml', [
            'form' => $form->getView()
        ]);
    }

    public function treeAction()
    {
        $req = $this->getRequest();

        $menuTree = (object) [
            'sid' => $req->getParam('sid'),
            'parent' => $req->getParam('parent'),
        ];
        
        return $this->render2('menu/tree.phtml', [
            'menuTree' => $menuTree,
            'rows' => $this->getEntityManager()->getRepository('menu_links')->fetchByMenuSidParent(
                $menuTree->sid, $menuTree->parent
            ),
        ]);
    }

    public function menusAction()
    {
        return $this->render2('menu/menus.phtml', [
            'rows' => $this->getEntityManager()->getRepository('menu')->getDbTable()->fetchAll(),
        ]);
    }

    public function editAction()
    {
        $req = $this->getRequest();

        $entity = $this->getEntityManager()->findOrCreate('menu', $req->getParam('id'));

        /** @var \App\Form\AbstractForm $form */
        $form = $this->get('formBuilder')->formBuilder(EditType::class, $entity, [
            'locales' => [
                1 => 'pl_PL',
                2 => 'de_DE',
                3 => 'en_GB',
            ],
            'sites' => $this->get('sites')->listSites(),
            'hydrator' => new ClassMethodsHydrator(),
            'form_options' => [
                'method' => 'post',
            ]
        ])->getForm();

        if ($req->isPost()) {
            if ($form->isValid($req->getPost())) {
                if ($this->getEntityManager()->save($entity)) {
                    $this->_helper->log->info("saved", [
                        // @TODO include in log
                        'user' => $this->_helper->auth->getIdentity()->getId(),
                        'entityId' => $entity->getId(),
                    ]);

                    $this->_helper->flashMessenger('saved', 'success');
                    return $this->redirect($this->_helper->url->url(['controller' => 'menu'] + $entity->getPKs(), 'panel/edit'));
                }
            }
        }

        return $this->render2('menu/edit.phtml', [
            'form' => $form->getView()
        ]);
    }
}
