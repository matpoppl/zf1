<?php

use Panel\Controller\AbstractController;
use Panel\User\EditForm;
use App\Crypto\Hasher\PasswordHasherInterface;
use App\User\Entity\UserEntity;

class Panel_UserController extends AbstractController
{
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository(UserEntity::class);

        /** @see \App\EntityManager\DbTable\AbstractDbTable */
        $rows = $repo->queryAll();

        if ($rows instanceof \PDOStatement) {
            $rows->setFetchMode(\PDO::FETCH_OBJ);
        }

        //$repo->select()->cols()->as()->where()->order()->offset($pager->queryOffser)->limit($pager->queryLimit);

        return $this->render2('user/index.phtml', [
            'rows' => $rows,
        ]);
    }

    public function profileAction()
    {
        $url = $this->_helper->url->url([], 'panel/profile');

        $form = new EditForm([
            'method' => 'post',
            'action' => $url,
        ]);

        $form->get('username')
        ->setRequired(false)
        ->setAttrib('disabled', true);

        $form->setDefaults([
            'username' => $this->_helper->auth()->getIdentity()->getId(),
        ]);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $this->_helper->flashMessenger('saved', 'success');
                return $this->redirect($url);
            }
        }

        return $this->render2('user/edit.phtml', [
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $userId = (int) $this->getRequest()->getParam('id', 0);

        $url = $this->_helper->url->url([
            'id' => $userId,
        ], 'panel/user-edit');

        $dbTable = new Zend_Db_Table([
            'name' => 'users',
            'adapter' => $this->get('Db'),
        ]);

        /** @var \Zend_Db_Table_Row $user */
        if ($userId > 0) {
            $user = $dbTable->find($userId)->current();
        } else {
            $user = $dbTable->createRow();
        }

        $form = new EditForm([
            'method' => 'post',
            'action' => $url,
        ]);

        $form->setDefaults($user->toArray());

        $form->getElement('username')->addValidator('Db_NoRecordExists', true, [
            'table' => 'users',
            'field' => 'username',
            'exclude' => ($userId > 0) ? [
                'field' => 'username',
                'value' => $user->username,
            ] : null,
            'adapter' => $this->get('Db'),
        ]);

        if ($userId < 1) {
            $form->getElement('password1')->setRequired(true);
            $form->getElement('password2')->setRequired(true);
        }

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $user->setFromArray($form->getValues());

                if ($newPassword = $form->getValue('password2')) {
                    $passwordHashes = $this->get(PasswordHasherInterface::class);
                    $user->password = $passwordHashes->hash($newPassword);
                }

                if (! $user->save()) {
                    throw new \RuntimeException('EntityManager save error');
                }

                $this->_helper->flashMessenger('saved', 'success');
                return $this->redirect($url);
            }
        }


        return $this->render2('user/edit.phtml', [
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        if ($this->_helper->security->verifyCSRF()) {
            $count = 0;
            foreach ($this->getRequest()->getPost('id') as $userId) {
                foreach ($this->get('EntityManager')->find('users', $userId) as $user) {
                    if ($user->username === $this->_helper->auth->getIdentity()->getId()) {
                        $this->_helper->flashMessenger('Can\'t delete self', 'error');
                    } else {
                        $count += $user->delete() ? 1 : 0;
                    }
                }
            }

            if ($count > 0) {
                $this->_helper->flashMessenger('Deleted', 'success');
            } else {
                $this->_helper->flashMessenger('User not found', 'error');
            }
        }

        return $this->redirect($this->_helper->url2->getUrlReferer());
    }

    public function signoutAction()
    {
        $this->_helper->auth()->clearIdentity();
        return $this->_helper->redirector->gotoRoute([], 'panel/home');
    }
}
