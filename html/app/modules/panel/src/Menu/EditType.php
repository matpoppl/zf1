<?php

namespace Panel\Menu;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'Text', [
            'label' => 'name',
            'class' => 'input-text',
            'maxlength' => 100,
            'filters' => ['StringTrim'],
            'required' => true,
            'attribs' => [
                'required' => true,
                'autofocus' => true,
            ],
            'validators' => [
                ['StringLength', true, [
                    'max' => 100,
                ]],
            ],
        ]);

        $builder->add('sid', 'Text', [
            'label' => 'sID',
            'class' => 'input-text',
            'maxlength' => 100,
            'filters' => ['StringTrim'],
            'required' => true,
            'attribs' => [
                'required' => true,
            ],
            'validators' => [
                ['StringLength', true, [
                    'max' => 100,
                ]],
            ],
        ]);

        $builder->add('site', 'Select', [
            'label' => 'site',
            'class' => 'input-select',
            'required' => true,
            'multiOptions' => [ '' => '-- choose --' ] + ($options['sites'] ?? []),
        ]);

        $builder->add('locale', 'Select', [
            'label' => 'locale',
            'class' => 'input-select',
            'required' => true,
            'multiOptions' => [ '' => '-- choose --' ] + ($options['locales'] ?? []),
        ]);

        $builder->add('submit', 'Button', [
            'label' => 'save',
            'class' => 'btn btn--primary',
            'attribs' => [
                'type' => 'submit',
            ],
        ]);
    }
}
