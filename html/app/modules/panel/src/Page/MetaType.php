<?php

namespace Panel\Page;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class MetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'Text', [
            'label' => 'META title',
            'class' => 'input-text',
            'maxlength' => 200,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 200,
                ]],
            ],
        ]);

        $builder->add('keywords', 'Text', [
            'label' => 'META keywords',
            'class' => 'input-text',
            'maxlength' => 200,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 200,
                ]],
            ],
        ]);

        $builder->add('description', 'Textarea', [
            'label' => 'META description',
            'class' => 'input-text',
            'maxlength' => 300,
            'rows' => 2,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 300,
                ]],
            ],
        ]);
    }
}
