<?php

namespace Panel\Menu;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;
use Panel\Page\ModelType;
use Panel\Page\MetaType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addSubForm('page', ModelType::class, [
            'subform_options' => [
                'legend' => 'page entity',
            ],
        ]);

        $builder->addSubForm('link', LinkType::class, [
            'subform_options' => [
                'legend' => 'Menu node entity',
            ],
        ]);


        $builder->addSubForm('meta', MetaType::class, [
            'subform_options' => [
                'legend' => 'META information entity',
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
