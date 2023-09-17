<?php

namespace Panel\Menu;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', 'Text', [
            'label' => 'menu label',
            'class' => 'input-text',
            'maxlength' => 100,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 100,
                ]],
            ],
        ]);

        $builder->add('url', 'Text', [
            'label' => 'menu URL',
            'class' => 'input-text',
            'maxlength' => 255,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 255,
                ]],
            ],
        ]);

        $builder->add('visible', 'Radio', [
            'label' => 'menu visible',
            'class' => 'input-radio',
            'maxlength' => 255,
            'filters' => ['Int'],
            'multiOptions' => [
                1 => 'yes',
                0 => 'no',
            ],
            'label_class' => 'radio-label',
            'separator' => '',
            'decorators' => [
                'ViewHelper',
                ['HtmlTag', [
                    'class' => 'form__elem__view',
                ]],
                'Label',
                [
                    'decorator' => [
                        'elem-wrap' => 'HtmlTag',
                    ],
                    'options' => [
                        'class' => 'form__elem',
                    ],
                ],
                'Errors',
            ],
        ]);

        $builder->add('parent', 'Select', [
            'label' => 'menu parent node',
            'class' => 'input-select',
            'multiOptions' => [
                0 => '-- menu root --',
            ],
        ]);
    }
}
