<?php

namespace matpoppl\DbEntityGenerator;

class ClassWriter
{
    /** @var string[] */
    private $nsPRS0 = [];
    /** @var string[] */
    private $nsPRS4 = [];

    public function addPRS0($ns, $dir)
    {
        $this->nsPRS0[$ns] = rtrim($dir, '\\/') . '/';
        return $this;
    }

    public function addPRS4($ns, $dir)
    {
        $this->nsPRS4[$ns] = rtrim($dir, '\\/') . '/';
        return $this;
    }

    /**
     *
     * @param string $fqName
     * @return string|NULL
     */
    public function match($fqName)
    {
        // PSR-4
        foreach ($this->nsPRS4 as $ns => $dir) {
            if (0 !== strpos($fqName, $ns)) {
                continue;
            }

            $filename = substr($fqName, strlen($ns));

            return $dir . str_replace('\\', '/', $filename) . '.php';
        }

        // PSR-0
        foreach ($this->nsPRS0 as $ns => $dir) {
            if (0 !== strpos($fqName, $ns)) {
                continue;
            }

            return $dir . str_replace(['\\', '_'], '/', $fqName) . '.php';
        }

        return null;
    }

    public function write(ClassGenerator $classGen)
    {
        $pathname = $this->match($classGen->getFQName());

        if (! $pathname) {
            throw new \RuntimeException('Can\'t match class pathname');
        }

        $dirname = dirname($pathname);

        is_dir($dirname) || mkdir($dirname, 0711, true);

        if (file_put_contents($pathname, "<?php\n" . $classGen) < 1) {
            throw new \RuntimeException('Class  write error');
        }

        return $this;
    }
}
