<?php

namespace App\Form\Builder;

use App\Form\Type\TypeInterface;
use matpoppl\Hydrator\HydratorInterface;
use matpoppl\Hydrator\ClassMethodsHydrator;

class FormBuilder implements FormBuilderInterface
{
    /** @var FormBuilderService */
    protected $fbs;
    /** @var \App\Form\Type\TypeInterface|NULL */
    protected $type;
    /** @var object|NULL */
    protected $data;
    /** @var array|NULL */
    protected $options;
    /** @var \Zend_Form */
    protected $form;
    /** @var HydratorInterface */
    protected $hydrator = null;
    /** @var bool */
    protected $appendCSRF = false;

    public function __construct(FormBuilderService $fbs, TypeInterface $type = null, $data = null, array $options = null)
    {
        $this->fbs = $fbs;
        $this->type = $type;
        $this->data = $data;
        $this->options = $options;

        if (null !== $options) {
            $this->setOptions($options);
        }

        $this->init();
    }

    protected function init()
    {
        $this->form = new \Zend_Form(($this->options['form_options'] ?? []) + [
            'decorators' => [
                'FormErrors',
                'FormElements',
                ['HtmlTag', [
                    'class' => 'form__fieldset-main',
                ]],
                ['Fieldset', [
                    'class' => 'form__fieldset',
                ]],
                'Form',
            ],
            'elementDecorators' => [
                'ViewHelper',
                'Label',
                ['Description', [
                    'tag' => 'small',
                    'class' => 'form__elem__hint',
                ]],
                'Tooltip',
                ['Errors', [
                    'class' => 'form__elem__errors',
                ]],
                ['HtmlTag', [
                    'class' => 'form__elem',
                ]],
            ],
        ]);

        if (null !== $this->type) {
            $this->type->buildForm($this, $this->options);
        }

        if ($this->appendCSRF) {
            $this->add('csrf', 'Hash');
        }
    }

    public function setAppendCsrf($appendCSRF)
    {
        $this->appendCSRF = $appendCSRF;
        return $this;
    }

    public function setOptions(array $options)
    {
        $hydrator = new ClassMethodsHydrator();
        $hydrator->hydrate($this, $options);
        return $this;
    }

    public function addSubForm($name, $type, array $options = null)
    {
        $subFormBuilder = new SubFormBuilder($this->fbs, $this->fbs->resolveType($type), null, $options);
        $this->form->addSubForm($subFormBuilder->getForm()->getView(), $name);
        return $this;
    }

    public function add($name, $type, array $options = null)
    {
        $this->form->addElement($type, $name, $options);
        return $this;
    }

    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /** @return Form */
    public function getForm()
    {
        if (is_object($this->data) && $this->hydrator) {
            $this->form->setDefaults($this->hydrator->extract($this->data));
        } elseif (is_array($this->data)) {
            $this->form->setDefaults($this->data);
        }

        $form = new Form($this->form, $this->data, $this->options);

        if ($this->hydrator) {
            $form->setHydrator($this->hydrator);
        }

        return $form;
    }
}
