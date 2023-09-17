<?php

namespace App\Uploads\Storage;

use RecursiveDirectoryIterator as DirIter;
use RecursiveIteratorIterator as IterIter;
use matpoppl\Paths\Path;

class TempDir extends Path
{
    public function __construct($pathname)
    {
        is_dir($pathname) || mkdir($pathname, 0755, true);
        parent::__construct($pathname);
    }

    public function __destruct()
    {
        $this->delete();
    }

    public function delete()
    {
        $pathname = '' . $this;

        $iter = new DirIter($pathname, DirIter::CURRENT_AS_FILEINFO | DirIter::SKIP_DOTS);
        $iter = new IterIter($iter, IterIter::CHILD_FIRST);

        /** @var \SplFileInfo $splFile */
        foreach ($iter as $splFile) {
            if ($splFile->isDir()) {
                rmdir($splFile->getPathname());
            } else {
                unlink($splFile->getPathname());
            }
        }

        rmdir($pathname);

        return $this;
    }
}
