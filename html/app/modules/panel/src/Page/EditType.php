<?php

namespace Panel\Page;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addSubForm('model', ModelType::class, [
            'subform_options' => [
                'legend' => 'Page',
            ],
        ]);

        $builder->addSubForm('meta', MetaType::class, [
            'subform_options' => [
                'legend' => 'META',
            ],
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
