<?php

namespace App\Form\Builder;

use App\Form\Type\TypeInterface;

class SubFormBuilder extends FormBuilder
{
    protected function init()
    {
        $subFormOptions = $this->options['form_options'] ?? [];
        $subFormOptions = $this->options['subform_options'] ?? $subFormOptions;

        $this->form = new \Zend_Form_SubForm($subFormOptions + [
            'decorators' => [
                'FormErrors',
                'FormElements',
                ['HtmlTag', [
                    'class' => 'form__fieldset-main',
                ]],
                ['Fieldset', [
                    'class' => 'form__fieldset',
                ]],
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

        $this->type->buildForm($this, $this->options ?: []);
    }
}
