<?php

namespace App\Form\Builder;

use matpoppl\ServiceManager\ContainerInterface;
use matpoppl\ServiceManager\ContainerAwareInterface;
use App\Form\Type\CallbackType;
use App\Form\Type\TypeInterface;

class FormBuilderService implements ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @param string|\Closure|\App\Form\Type\TypeInterface $type
     * @return \App\Form\Type\TypeInterface
     */
    public function resolveType($type)
    {
        if (is_string($type)) {
            if ($this->container && $this->container->has($type)) {
                $type = $this->container->get($type);
            } elseif (class_exists($type)) {
                $type = new $type();
            } else {
                throw new \UnexpectedValueException("Type `{$type}` not found");
            }
        } elseif ($type instanceof \Closure) {
            $type = new CallbackType($type);
        }

        if (! ($type instanceof TypeInterface)) {
            throw new \UnexpectedValueException("Unsupported Type `{$type}`");
        }

        return $type;
    }

    /**
     *
     * @param string|\Closure|\App\Form\Type\TypeInterface $type
     * @param object $data
     * @param array $options
     * @return \App\Form\Builder\FormBuilder
     */
    public function formBuilder($type = null, $data = null, array $options = null)
    {
        return new \App\Form\Builder\FormBuilder($this, $type ? $this->resolveType($type) : $type, $data, $options);
    }
}
