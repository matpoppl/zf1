<?php

namespace App\Form;

abstract class AbstractForm extends \Zend_Form
{
    /**
     *
     * @param string $id
     * @throws \DomainException
     * @return \Zend_Form_Element|\Zend_Form|NULL
     */
    public function get(string $id)
    {
        $ret = $this->getSubForm($id) ?: $this->getElement($id);

        if (null === $ret) {
            throw new \DomainException("Form element/subform `{$id}` not found");
        }

        return $ret;
    }
}
