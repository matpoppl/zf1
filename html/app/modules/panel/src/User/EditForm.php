<?php

namespace Panel\User;

use App\Form\AbstractForm;

class EditForm extends AbstractForm
{
    public function init()
    {
        $this->setOptions([
            'elements' => [
                [
                    'name' => 'username',
                    'type' => 'Text',
                    'options' => [
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
                    ]
                ], [
                    'name' => 'password1',
                    'type' => 'Password',
                    'options' => [
                        'label' => 'New password',
                        'attribs' => [
                            'maxlength' => 200,
                            'class' => 'input-text',
                        ],
                    ]
                ], [
                    'name' => 'password2',
                    'type' => 'Password',
                    'options' => [
                        'label' => 'Repeat password',
                        'attribs' => [
                            'maxlength' => 200,
                            'class' => 'input-text',
                        ],
                        'validators' => [
                            ['StringLength', true, [
                                'min' => 4,
                                'max' => 200,
                            ]],
                            /** @see \Zend_Validate_Identical */
                            ['Identical', true, [
                                'token' => 'password1',
                                'strict' => true,
                            ]],
                        ],
                    ]
                ], [
                    'name' => 'submit',
                    'type' => 'Button',
                    'options' => [
                        'type' => 'submit',
                        'label' => 'save',
                        'class' => 'btn btn--primary',
                    ]
                ],
            ]
        ]);
    }
}
