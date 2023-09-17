<?php

namespace matpoppl\DbEntityGenerator;

use PHPUnit\SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

class ClassGenerator
{
    /** @var string|null */
    private $namespace = null;
    /** @var bool */
    private $isAbstract = false;
    /** @var string */
    private $name;
    /** @var string|null */
    private $extends = null;
    /** @var string[] */
    private $implements = [];
    /** @var string[] */
    private $uses = [];

    /** @var ClassProperty[] */
    private $properties = [];
    /** @var ClassMethod[] */
    private $methods = [];

    /** @var DocBlock|NULL */
    private $docblock = null;

    public function __construct($name, $ns = null)
    {
        $this->name = $name;
        $this->namespace = $ns;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFQName()
    {
        return $this->namespace . '\\' . $this->name;
    }

    public function setProperties(array $properties)
    {
        $this->properties = [];

        foreach ($properties as $key => $property) {
            if (is_string($key)) {
                $this->addProperty(new ClassProperty($key, $property));
            } else {
                $this->addProperty($property);
            }
        }

        return $this;
    }

    public function addProperty($property)
    {
        if (! ($property instanceof ClassProperty)) {
            $factory = new ClassPropertyFactory();
            $property = $factory->create($property);
        }

        if (! ($property instanceof ClassProperty)) {
            throw new \InvalidArgumentException('Unsupported property type');
        }

        $this->properties[$property->getName()] = $property;

        return $this;
    }

    public function addMethod($method)
    {
        if (! ($method instanceof ClassMethod)) {
            throw new \InvalidArgumentException('Unsupported method type');
        }

        $this->methods[$method->getName()] = $method;

        return $this;
    }

    /**
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return ClassProperty
     */
    public function createProperty($name)
    {
        if (array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property already exists `'.$name.'`');
        }

        $this->addProperty(new ClassProperty($name));

        return $this->getProperty($name);
    }

    /**
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return ClassProperty
     */
    public function getProperty($name)
    {
        if (! array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property dont exists `'.$name.'`');
        }

        return $this->properties[$name];
    }

    /**
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return ClassMethod
     */
    public function createMethod($name)
    {
        if (array_key_exists($name, $this->methods)) {
            throw new \InvalidArgumentException('Method already exists `'.$name.'`');
        }

        $this->addMethod(new ClassMethod($name));

        return $this->getMetohd($name);
    }

    /**
     *
     * @param string $name
     * @throws \InvalidArgumentException
     * @return ClassMethod
     */
    public function getMetohd($name)
    {
        if (! array_key_exists($name, $this->methods)) {
            throw new \InvalidArgumentException('Method dont exists `'.$name.'`');
        }

        return $this->methods[$name];
    }

    public function addExtend($extend)
    {
        $this->extends = $extend;
        return $this;
    }

    public function addUse($use)
    {
        $this->uses[] = $use;
        return $this;
    }

    public function setAbstract($isAbstract)
    {
        $this->isAbstract = !! $isAbstract;
        return $this;
    }

    public function __toString()
    {
        $data = [
            '@NAMESPACE@' => $this->namespace ? 'namespace '.rtrim($this->namespace, '\\/').';' : '',
            '@USE@' => $this->uses ? 'use ' . implode(";\nuse ", $this->uses) . ';' : '',
            '@DOCBLOCK@' => ''.$this->docblock,
            '@ABSTRACT@' => $this->isAbstract ? 'abstract' : '',
            '@CLASS_NAME@' => $this->name,
            '@EXTENDS@' => $this->extends ? ' extends ' . $this->extends : null,
            '@IMPLEMENTS@' => $this->implements ? ' implements ' . implode(', ', $this->implements) : null,
            '@PROPERTIES@' => "\t" . str_replace("\n", "\n\t", implode("\n\n", $this->properties)),
            '@METHODS@' => "\t" . str_replace("\n", "\n\t", implode("\n\n", $this->methods)),
        ];

        $template = <<<EOF
@NAMESPACE@

@USE@

@DOCBLOCK@
@ABSTRACT@ class @CLASS_NAME@@EXTENDS@@IMPLEMENTS@
{
@PROPERTIES@
@METHODS@
}
EOF;

        return trim(strtr($template, $data));
    }
}
