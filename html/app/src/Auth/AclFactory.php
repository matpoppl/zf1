<?php

namespace App\Auth;

use Zend_Acl;

class AclFactory
{
    public function create(array $config)
    {
        $acl = new Zend_Acl();

        if (isset($config['roles'])) {
            foreach ($config['roles'] as $role => $parents) {
                if (is_int($role)) {
                    $acl->addRole($parents);
                } else {
                    $acl->addRole($role, $parents);
                }
            }
        }

        if (isset($config['resources'])) {
            foreach ($config['resources'] as $resource => $parent) {
                if (is_int($resource)) {
                    $acl->addResource(rtrim($parent, '/') . '/');
                } else {
                    $acl->addResource(rtrim($resource, '/') . '/', $parent);
                }
            }
        }

        if (isset($config['allow'])) {
            foreach ($config['allow'] as $resource => $roles) {
                $resource = rtrim($resource, '/') . '/';
                if (! $acl->has($resource)) {
                    $acl->addResource($resource);
                }
                $acl->allow($roles, $resource);
            }
        }

        return $acl;
    }
}
