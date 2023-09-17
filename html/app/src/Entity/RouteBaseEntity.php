<?php

namespace App\Entity;

use App\EntityManager\AbstractEntity;

abstract class RouteBaseEntity extends AbstractEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $site;

    /**
     * @var int
     */
    protected $locale;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $mvcPath;
    public function getTableName()
    {
        return 'routes';
    }

    public function getPKs()
    {
        return ['id' => $this->id];
    }

    public function getClassAlias()
    {
        return 'Route';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param int $site
     * @return self
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param int $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getMvcPath()
    {
        return $this->mvcPath;
    }

    /**
     * @param string $mvcPath
     * @return self
     */
    public function setMvcPath($mvcPath)
    {
        $this->mvcPath = $mvcPath;
        return $this;
    }
}
