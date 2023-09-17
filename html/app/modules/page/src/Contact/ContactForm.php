<?php

namespace Page\Contact;

class ContactForm extends \Zend_Form
{
    public function init()
    {
        $this->setOptions([
            'legend' => 'Contact form',
            'attribs' => [
                'class' => 'form form--contact',
            ],
            'elements' => [
                [
                    /** @see Zend_Form_Element_Text */
                    /** @see Zend_View_Helper_FormText */
                    'name' => 'fullname',
                    'type' => 'Text',
                    'options' => [
                        'label' => 'full name',
                        'required' => true,
                        'attribs' => [
                            'class' => 'input-text',
                            'placeholder' => 'enter your name',
                        ],
                        'validators' => [
                            ['StringLength', true, [
                                'min' => 3,
                                'max' => 200,
                            ]],
                        ],
                    ],
                ], [
                    'name' => 'email',
                    'type' => 'Text',
                    'options' => [
                        'label' => 'email address',
                        'description' => '* please enter your e-mail address',
                        'required' => true,
                        'attribs' => [
                            'class' => 'input-text',
                            'placeholder' => 'enter e-mail address',
                        ],
                    ],
                ], [
                    'name' => 'message',
                    'type' => 'Textarea',
                    'options' => [
                        'label' => 'Your message',
                        'description' => '* please enter your message',
                        'required' => true,
                        'attribs' => [
                            'class' => 'input-text',
                        ],
                    ],
                ], [
                    /** @see Zend_Form_Element_Hash */
                    'name' => 'csrf',
                    'type' => 'Hash',
                    'options' => [
                        'salt' => '56d.,mrthio09876tuiop-09u',
                    ],
                ], [
                    /** @see Zend_Form_Element_Captcha */
                    'name' => 'vrfy',
                    'type' => 'Captcha',
                    'options' => [
                        /** @see Zend_Captcha_Dumb */
                        'captcha' => 'Dumb',
                        'captchaOptions' => [],
                        'decorators' => [
                            'Label',
                            'Errors',
                            ['Description', [
                                'tag' => 'small',
                                'class' => 'form__hint',
                            ]],
                            ['HtmlTag', [
                                'class' => 'form__elem',
                            ]],
                        ],
                    ],
                ], [
                    'name' => 'submit',
                    'type' => 'Button',
                    'options' => [
                        'label' => 'Submit',
                        'type' => 'submit',
                    ],
                ],
            ],
            'decorators' => [
                'FormErrors',
                'FormElements',
                ['HtmlTag', [
                    'class' => 'form__fieldset-div',
                ]],
                'Fieldset',
                'Form',
            ],

            'displayGroups' => [
                'inputs' => [
                    'elements' => ['fullname', 'email', 'message', 'vrfy'],
                    'options' => [
                        'decorators' => [
                            'FormElements',
                        ],
                    ],
                ],
                'buttons' => [
                    'elements' => ['csrf', 'submit'],
                    'options' => [
                        'decorators' => [
                            'FormElements',
                            ['HtmlTag', [
                                'class' => 'form__buttons',
                            ]],
                        ],
                    ],
                ],
            ],
        ]);

        $this->setElementDecorators([
            'Label',
            'ViewHelper',
            'Errors',
            ['Description', [
                'tag' => 'small',
                'class' => 'form__hint',
            ]],
            ['HtmlTag', [
                'class' => 'form__elem',
            ]],
        ], ['fullname', 'email', 'message']);

        $this->setElementDecorators([
            'ViewHelper',
            'Errors',
        ], ['csrf', 'submit']);
    }
}
