<?php

use Panel\Controller\AbstractController;
use App\I18n\Translator\FileWriter;

class Panel_ConfigController extends AbstractController
{
    public function translationsAction()
    {
        $url = $this->_helper->url->url([], 'panel/translations');

        /** @var \App\Form\Builder\Form $form */
        $form = $this->get('formBuilder')
        ->formBuilder(null, null, [
            'form_options' => [
                'action' => $url,
                'method' => 'post',
                'legend' => 'New translation',
            ],
        ])
        ->add('newKey', 'Text', [
            'label' => 'Translation key',
            'class' => 'input-text',
            'required' => true,
            'maxlength' => 300,
            'filters' => ['StringTrim'],
            'validators' => [['StringLength', true, ['min' => 1, 'max' => 300]]],
        ])
        ->add('newTrans', 'Text', [
            'label' => 'Translation',
            'class' => 'input-text',
            'required' => true,
            'maxlength' => 300,
            'filters' => ['StringTrim'],
            'validators' => [['StringLength', true, ['min' => 1, 'max' => 300]]],
        ])
        ->add('submit', 'Submit', [
            'label' => 'add',
            'class' => 'btn btn--primary',
        ])->getForm();

        if ($this->_request->isPost()) {
            /** @var \Zend_Translate $trans */
            $trans = $this->get('translate');

            $trans->clearCache();

            /** @var \Zend_Translate_Adapter_Array $transAdapter */
            $transAdapter = $trans->getAdapter();

            if ($this->_request->getPost('newKey') && $form->isValid($this->_request->getPost())) {
                $data = $form->getData();

                $this->getWriter($transAdapter, $transAdapter->getLocale())
                    ->add($data['newKey'], $data['newTrans'])
                    ->close();

                $this->_helper->flashMessenger('saved', 'success');
            }

            $transField = clone $form->get('newTrans');
            foreach ($transAdapter->getList() as $locale) {
                if (! $this->_request->getPost($locale)) {
                    continue;
                }

                $this->getWriter($transAdapter, $locale)
                ->setData(array_map(function ($trans) use ($transField) {
                    return $transField->isValid($trans) ? $transField->getValue() : null;
                }, $this->_request->getPost($locale)))
                ->close();

                $this->_helper->flashMessenger('saved', 'success');
            }

            return $this->redirect($url);
        }

        return $this->render2('config/translations.phtml', [
            'form' => $form->getView()->setTranslator($this->get('translate')),
        ]);
    }

    /**
     *
     * @param \Zend_Translate_Adapter $dir
     * @param string $locale
     * @return \App\I18n\Translator\FileWriter
     */
    private function getWriter(\Zend_Translate_Adapter $transAdapter, $locale)
    {
        $pathname = $transAdapter->getOptions('content') . '/' . $locale . '.php';
        return new FileWriter($pathname);
    }
}
