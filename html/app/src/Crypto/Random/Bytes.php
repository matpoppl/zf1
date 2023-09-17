<?php

namespace App\Crypto\Random;

use App\Crypto\RandomBytesInterface;

class Bytes implements RandomBytesInterface
{
    public function randomInt($min, $max): int
    {
        if (function_exists('random_int')) {
            return random_int($min, $max);
        }

        return mt_rand($min, $max);
    }

    public function randomBytes(int $length): string
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return openssl_random_pseudo_bytes($length);
        }
        if (function_exists('mcrypt_create_iv')) {
            return mcrypt_create_iv($length);
        }

        $chars = ['c*'];
        for ($i = 0; $i < $length; $i++) {
            $chars[] = $this->randomInt(0, PHP_INT_MAX);
        }
        return pack(...$chars);
    }
}
