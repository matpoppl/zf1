<?php

namespace App\Form\Builder;

interface FormBuilderInterface
{
    /** @return \Zend_Form */
    public function getForm();

    /**
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @return FormBuilderInterface
     */
    public function add($name, $type, array $options = null);

    /**
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @return FormBuilderInterface
     */
    public function addSubForm($name, $type, array $options = null);
}
