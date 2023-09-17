<?php

namespace App\Form\Type;

use App\Form\Builder\FormBuilderInterface;

class CallbackType extends AbstractType
{
    private $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $cb = $this->callback;
        $cb($builder, $options);
    }
}
