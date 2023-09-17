<?php

namespace App\Form\Builder;

use PHPUnit\Framework\TestCase;
use matpoppl\Hydrator\ObjectPropertyHydrator;

class FormBuilderServiceTest extends TestCase
{
    /**
     *
     * @param object $data
     * @return \App\Form\Builder\FormBuilder
     */
    private function createFormBuilder($data)
    {
        $fbs = new FormBuilderService();
        $fb = $fbs->formBuilder(function ($builder) {
            $builder->add('username', 'Text', [
                'validators' => [
                    ['StringLength', true, [
                        'min' => 2,
                    ]],
                ],
            ]);
        }, $data, []);

        $fb->setHydrator(new ObjectPropertyHydrator());
        $fb->setAppendCsrf(false);

        return $fb;
    }

    public function testGetterSetter()
    {
        $data = new \stdClass();
        $data->username = __CLASS__;

        $fb = $this->createFormBuilder($data);

        self::assertEquals(__CLASS__, $fb->getForm()->get('username')->getValue());

        $form = $fb->getForm();

        self::assertTrue($form->isValid([
            'username' => __METHOD__,
        ]));

        self::assertEquals(__METHOD__, $data->username);
    }
}
