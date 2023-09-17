<?php

namespace App\Crypto\Hasher;

use App\Crypto\RandomBytesInterface;

class CryptHasher implements PasswordHasherInterface
{
    /** @var RandomBytesInterface */
    private $randomSource;

    public function __construct(RandomBytesInterface $randomSource)
    {
        $this->randomSource = $randomSource;
    }

    public function hash($password)
    {
        if (CRYPT_SHA512) {
            // 1000 and a maximum of 999,999,999
            $salt = '$6$rounds=10000$' . $this->randomSource->randomBytes(16);
        } elseif (CRYPT_SHA256) {
            // 1000 and a maximum of 999,999,999
            $salt = '$5$rounds=10000$' . $this->randomSource->randomBytes(16);
        } elseif (CRYPT_BLOWFISH) {
            // 04-31
            $salt = '$2a$11$' . $this->randomSource->randomBytes(22);
        } elseif (CRYPT_MD5) {
            $salt = '$1$ ' . $this->randomSource->randomBytes(12);
        } elseif (CRYPT_EXT_DES) {
            $salt = '_1000' . $this->randomSource->randomBytes(4);
        } elseif (CRYPT_EXT_DES) {
            $salt = $this->randomSource->randomBytes(2);
        }

        $hash = crypt($password, $salt);

        if (strlen($hash) < 13) {
            throw new \RuntimeException('Generated hash is too short');
        }

        return $hash;
    }

    private function randomBytes($length)
    {
        $bytes = $this->randomSource->randomBytes($length + 20);
        $bytes = base64_encode($bytes);
        $bytes = preg_replace('#[^0-9A-Za-z]+#', '', $bytes);
        return substr($bytes, 0, $length - 20);
    }

    public function verify($password, $hash)
    {
        return hash_equals($hash, $password);
    }

    public function needsRehash($hash)
    {
        $matched = null;
        if (preg_match('#^\$(\d)#', $hash, $matched) < 1) {
            return true;
        }

        return $matched[0] > 1;
    }
}
