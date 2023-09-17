<?php

namespace App\Form\Type;

use App\Form\Builder\FormBuilderInterface;

interface TypeInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options);
}
