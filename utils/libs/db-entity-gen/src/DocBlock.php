<?php

namespace matpoppl\DbEntityGenerator;

class DocBlock
{
    /**
     *
     * @var string[][]
     */
    private $tags = [];

    /**
     *
     * @param string $tagName
     * @param string $datatype
     * @param string|NULL $name
     * @param string|NULL $description
     * @return DocBlock
     */
    public function add($tagName, $datatype, $name = null, $description = null)
    {
        if (null === $name) {
            $this->tags[] = [$tagName, $datatype, $name, $description];
        } else {
            $this->tags[$name] = [$tagName, $datatype, $name, $description];
        }

        return $this;
    }

    public function __toString()
    {
        $ret = '';

        foreach ($this->tags as $tag) {
            list($tagName, $datatype, $name, $description) = $tag;
            if (null === $name) {
                $ret .= " * @{$tagName} {$datatype} {$description}\n";
            } else {
                $ret .= " * @{$tagName} {$datatype} \${$name} {$description}\n";
            }
        }

        return "/**\n{$ret} */";
    }
}
