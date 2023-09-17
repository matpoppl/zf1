<?php

namespace App\Uploads\Storage;

use App\Uploads\Entity\AttachmentEntity;
use matpoppl\Paths\Paths;

class Filesystem
{
    /** @var Paths */
    private $paths;

    public function __construct(Paths $paths)
    {
        $this->paths = $paths;
    }

    /** @return \App\Uploads\Storage\TempDir */
    public function createTempLocation()
    {
        $dir = $this->paths->get('tmp://uploads');
        $basename = $this->uniqueIn('' . $dir);
        return new TempDir($dir->append($basename));
    }

    private function uniqueIn($dir, $basename = null)
    {
        $dir = rtrim($dir, '\\/') . '/';
        $ext = '';
        $hash = '';

        if (null === $basename) {
            $basename = substr(md5(microtime(true)), 0, 6);
        } else {
            $ext = '.' . pathinfo($basename, \PATHINFO_EXTENSION);
            $basename = pathinfo($basename, \PATHINFO_FILENAME);
        }

        while (file_exists($dir . $basename . $hash . $ext)) {
            $hash = '-' . substr(md5(microtime(true)), 0, 6);
        }

        return $basename . $hash . $ext;
    }

    public function importUpload(string $pathname, AttachmentEntity $entity)
    {
        $dir = $this->paths->get('uploads://');

        $basename = $this->uniqueIn('' . $dir, basename($pathname));

        $mime = \mime_content_type($pathname);

        if (! $mime) {
            throw new \RuntimeException('MIME detection error');
        }

        if (! rename($pathname, '' . $dir->append($basename))) {
            throw new \RuntimeException('File move error');
        }

        $entity->setPath('uploads://' . $basename);

        $entity->setMime($mime);
    }
}
