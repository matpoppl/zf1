<?php

namespace Panel\Uploads;

use App\Form\Type\AbstractType;
use App\Form\Builder\FormBuilderInterface;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @see \Zend_Form_Element_File */
        /** @see \Zend_Filter_File_LowerCase */
        /** @see \Zend_Filter_File_Rename */
        /** @see \Zend_Filter_File_UpperCase */
        /** @see \Zend_Validate_File_Count */
        /** @see \Zend_Validate_File_Extension */
        /** @see \Zend_Validate_File_FilesSize */
        /** @see \Zend_Validate_File_ImageSize */
        /** @see \Zend_Validate_File_IsImage */
        /** @see \Zend_Validate_File_MimeType */
        /** @see \Zend_Validate_File_Upload */
        $builder->add('file', 'File', [
            'label' => 'file',
            'required' => true,
            'attribs' => [
                'autofocus' => true,
                'maxlength' => 200,
                'class' => 'input-text',
            ],
            'filters' => [],
            'validators' => [],
        ])->add('submit', 'Button', [
            'type' => 'submit',
            'label' => 'save',
            'class' => 'btn btn--primary',
        ]);
    }
}
