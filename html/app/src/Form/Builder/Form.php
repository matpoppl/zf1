<?php

namespace App\Form\Builder;

use matpoppl\Hydrator\HydrationInterface;

class Form
{
    /** @var object|array|NULL */
    private $data;
    /** @var array|NULL */
    private $options;
    /** @var \Zend_Form */
    private $form;
    /** @var HydrationInterface */
    private $hydrator;

    public function __construct(\Zend_Form $form, $data = null, array $options = null)
    {
        $this->form = $form;
        $this->data = $data;
        $this->options = $options;
    }

    public function getData()
    {
        return $this->data;
    }

    public function has($name)
    {
        if (null !== $this->form->getSubForm($name)) {
            return true;
        }

        if (null !== $this->form->getElement($name)) {
            return true;
        }

        return false;
    }

    public function get($name)
    {
        if (! $this->has($name)) {
            throw new \DomainException("Element `{$name}` not found");
        }

        return $this->form->getSubForm($name) ?: $this->form->getElement($name);
    }

    public function __call($name, $args)
    {
        if (! method_exists($this->form, $name)) {
            throw new \DomainException("Form method `{$name}` not found");
        }

        return $this->form->{$name}(...$args);
    }

    public function setHydrator(HydrationInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /** @return \Zend_Form */
    public function isValid($data)
    {
        if (! $this->form->isValid($data)) {
            return false;
        }

        if (is_object($this->data)) {
            if ($this->hydrator) {
                $this->hydrator->hydrate($this->data, $this->form->getValues());
            }
        } else {
            $this->data = $this->form->getValues();
        }

        return true;
    }

    /** @return \Zend_Form */
    public function getView()
    {
        $this->setElementDecorators([
            'ViewHelper',
        ], ['csrf', 'submit']);

        return $this->form;
    }
}
