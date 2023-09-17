<?php

namespace App\Site;

class SiteManager
{
    private $options;
    private $sites = [];

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function get($id = null)
    {
        if (null === $id) {
            $id = $this->options['default_site'];
        }

        if (array_key_exists($id, $this->sites)) {
            return $this->sites[$id];
        }

        $this->sites[$id] = new Site($this->options['sites'][$id]);

        return $this->sites[$id];
    }

    public function listSites()
    {
        return [1 => $_SERVER['HTTP_HOST']];
    }
}
