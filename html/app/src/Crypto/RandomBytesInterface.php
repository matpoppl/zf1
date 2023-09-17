<?php

namespace App\Crypto;

interface RandomBytesInterface
{
    public function randomBytes(int $length): string;
}
