<?php

namespace App\Uploads\Entity;

use App\EntityManager\AbstractEntity;

class AttachmentEntity extends AbstractEntity
{
    public const TYPE_FILE = 1;
    public const TYPE_IMAGE = 2;
    public const TYPE_AUDIO = 3;
    public const TYPE_VIDEO = 4;

    /** @var int */
    private $id = null;
    /** @var int */
    private $type;
    /** @var string */
    private $mime;
    /** @var string */
    private $path;


    public function getTableName()
    {
        return 'attachments';
    }

    public function getPKs()
    {
        return ['id'];
    }


    /**
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return number
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param number $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param number $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $mime
     */
    public function setMime($mime)
    {
        switch (strstr($mime, '/', true)) {
            case 'video':
                $this->setType(self::TYPE_VIDEO);
                break;
            case 'image':
                $this->setType(self::TYPE_IMAGE);
                break;
            case 'audio':
                $this->setType(self::TYPE_AUDIO);
                break;
            default:
                $this->setType(self::TYPE_FILE);
                break;
        }

        $this->mime = $mime;

        return $this;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}
