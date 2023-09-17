<?php

namespace App\Auth;

interface IdentityInterface
{
    /** @return string|int */
    public function getId();
    /** @return string[] */
    public function getRoles();
}
