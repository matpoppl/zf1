<?php

namespace Panel\User;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'Text', [
            'label' => 'Username',
            'required' => true,
            'attribs' => [
                'autofocus' => true,
                'maxlength' => 200,
                'class' => 'input-text',
            ],
            'filters' => [
                'StringTrim',
            ],
            'validators' => [
                ['StringLength', true, [
                    'min' => 2,
                    'max' => 200,
                ]],
            ],
        ])
        ->add('password1', 'Password', [
            'label' => 'New password',
            'attribs' => [
                'maxlength' => 200,
                'class' => 'input-text',
            ],
        ])
        ->add('password2', 'Password', [
            'label' => 'Repeat password',
            'attribs' => [
                'maxlength' => 200,
                'class' => 'input-text',
            ],
            'validators' => [
                ['StringLength', true, [
                    'min' => 2,
                    'max' => 200,
                ]],
                /** @see \Zend_Validate_Identical */
                ['Identical', true, [
                    'token' => 'password1',
                    'strict' => true,
                ]],
            ],
        ])->add('submit', 'Button', [
            'type' => 'submit',
            'label' => 'save',
            'class' => 'btn btn--primary',
        ]);
    }
}
