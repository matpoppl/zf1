<?php

namespace matpoppl\DbEntityGenerator;

class ClassProperty
{
    private $visibility = 'public';
    private $isConst = false;
    private $isStatic = false;
    private $datatype = null;
    private $value = null;
    private $name;

    /** @var DocBlock|NULL */
    private $docblock = null;

    public function __construct($name, $value = null)
    {
        $this->name = $name;
        if (null !== $value) {
            $this->setValue($value);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setConst($isConst)
    {
        $this->isConst = !! $isConst;
        return $this;
    }

    public function setStatic($static)
    {
        $this->isStatic = !! $static;
        return $this;
    }

    public function setValue($val, $autoDatatype = false)
    {
        $this->value = var_export($val, true);

        if ($autoDatatype) {
            $this->setDatatype(gettype($val));
        }

        return $this;
    }

    public function setDatatype($name)
    {
        $this->datatype = $name;
        return $this;
    }

    public function setVisibility($name)
    {
        $this->visibility = $name;
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
            '@VISIBILITY@' => $this->visibility,
            '@CONST@' => $this->isConst ? 'const' : '',
            '@STATIC@' => $this->isStatic ? 'static' : '',
            '@DATATYPE@' => $this->datatype,
            '@NAME@' => ($this->isConst ? '' : '$') . $this->name,
            '@VALUE@' => (null === $this->value) ? null : ' = ' . $this->value,
        ];

        $template = <<<EOT
@DOCBLOCK@
@VISIBILITY@ @CONST@ @STATIC@ @DATATYPE@ @NAME@@VALUE@;
EOT;

        return trim(preg_replace('#[ ]+#', ' ', strtr($template, $data)));
    }
}
