<?php

namespace matpoppl\Thumbnail;

class Generator
{
    public function __construct()
    {
        $this->adapter = new GDAdapter();
    }

    public function generate(Request $req)
    {
        return $this->adapter->generate($req);
    }

    public function info(string $pathname)
    {
        return $this->adapter->info($pathname);
    }
}
