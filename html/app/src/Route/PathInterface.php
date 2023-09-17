<?php

namespace App\Route;

interface PathInterface
{
    /** @return string */
    public function getPath();
    /** @return string */
    public function getFilename();
    /** @return string */
    public function getExtension();
}
