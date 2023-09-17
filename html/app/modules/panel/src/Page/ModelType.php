<?php

namespace Panel\Page;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class ModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'Text', [
            'label' => 'entity name',
            'class' => 'input-text',
            'required' => true,
            'maxlength' => 200,
            'attribs' => [
                'required' => true,
                'autofocus' => true,
            ],
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 200,
                ]],
            ],
        ]);

        $builder->add('content', 'Textarea', [
            'label' => 'entity content',
            'class' => 'input-text',
            'maxlength' => 65500,
            'filters' => ['StringTrim'],
            'validators' => [
                ['StringLength', true, [
                    'max' => 65500,
                ]],
            ],
        ]);
    }
}
