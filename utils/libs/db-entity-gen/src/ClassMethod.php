<?php

namespace matpoppl\DbEntityGenerator;

class ClassMethod
{
    private $name;
    private $visibility = 'public';
    private $isAbstract = false;
    private $isStatic = false;
    private $datatype = null;
    private $value = null;
    private $returnType = null;
    private $body = null;

    private $parameters = [];
    /** @var DocBlock|NULL */
    private $docblock = null;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAbstract($isAbstract)
    {
        $this->isAbstract = !! $isAbstract;
        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function setParameters(array $parameter)
    {
        $this->parameters = $parameter;
        return $this;
    }

    /** @return \matpoppl\DbEntityGenerator\DocBlock */
    public function getDocblock()
    {
        if (null === $this->docblock) {
            $this->docblock = new DocBlock();
        }
        return $this->docblock;
    }

    public function __toString()
    {
        $data = [
            '@DOCBLOCK@' => ''.$this->docblock,
            '@ABSTRACT@' => $this->isAbstract ? 'abstract' : '',
            '@VISIBILITY@' => $this->visibility,
            '@STATIC@' => $this->isStatic ? 'static' : '',
            '@NAME@' => $this->name,
            '@PARAMETERS@' => implode(', ', $this->parameters),
            '@RETURNTYPE@' => (null === $this->returnType) ? '' : ' : ' . $this->returnType,
            '@BODY@' => (null === $this->body) ? ';' : "\n{\n\t{$this->body}\n}",
        ];

        $template = <<<EOT
@DOCBLOCK@
@ABSTRACT@ @VISIBILITY@ @STATIC@ function @NAME@(@PARAMETERS@)@RETURNTYPE@@BODY@
EOT;

        return trim(preg_replace('#[ ]+#', ' ', strtr($template, $data)));
    }
}
