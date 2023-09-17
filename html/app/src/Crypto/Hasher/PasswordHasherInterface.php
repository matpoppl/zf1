<?php

namespace App\Crypto\Hasher;

interface PasswordHasherInterface
{
    /**
     *
     * @param string $password
     * @return string
     */
    public function hash($password);

    /**
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verify($password, $hash);

    /**
     *
     * @param string $hash
     * @return bool
     */
    public function needsRehash($hash);
}
