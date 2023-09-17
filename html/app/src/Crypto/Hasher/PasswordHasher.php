<?php

namespace App\Crypto\Hasher;

class PasswordHasher implements PasswordHasherInterface
{
    private $algo = \PASSWORD_DEFAULT;

    public function hash($password)
    {
        return password_hash($password, $this->algo);
    }

    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, $this->algo);
    }
}
