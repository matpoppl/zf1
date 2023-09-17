<?php

namespace App\Auth;

class Identity implements IdentityInterface
{
    private $id;
    private $roles;

    public function __construct($id, array $roles)
    {
        $this->id = $id;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}
