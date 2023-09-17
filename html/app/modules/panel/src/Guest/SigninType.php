<?php

namespace Panel\Guest;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class SigninType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'Text', [
            'label' => 'username',
            'required' => true,
            'attribs' => [
                'required' => true,
                'autofocus' => true,
                'class' => 'input-text',
            ]
        ])->add('password', 'Password', [
            'label' => 'password',
            'required' => true,
            'attribs' => [
                'required' => true,
                'class' => 'input-text',
            ],
        ])
        ->add('rememberMe', 'Checkbox', [
            'label' => 'remember me',
        ])
        ->add('submit', 'Button', [
            'label' => 'sign in',
            'attribs' => [
                'type' => 'submit',
                'class' => 'btn btn--primary',
            ],
        ]);
    }
}
