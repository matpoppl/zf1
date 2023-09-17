<?php

namespace App\Auth\Adapter;

use Zend_Auth_Result as Result;
use App\Auth\Identity;
use App\Crypto\Hasher\PasswordHasherInterface;

class DbAdapter implements \Zend_Auth_Adapter_Interface
{
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var PasswordHasherInterface */
    private $pwHasher;

    public function __construct($username, $password, PasswordHasherInterface $pwHasher)
    {
        $this->username = $username;
        $this->password = $password;
        $this->pwHasher = $pwHasher;
    }

    public function authenticate()
    {
        $messages = [];
        $code = Result::FAILURE;
        $identity = null;

        $dbTable = new \Zend_Db_Table([
            'name' => 'users',
        ]);

        $users = $dbTable->fetchAll([
            'username=?' => $this->username
        ]);

        $count = count($users);
        $user = $count > 0 ? $users->getRow(0) : null;

        if ($count < 1) {
            $code = Result::FAILURE_IDENTITY_NOT_FOUND;
            $messages[] = 'Username not found';
        } elseif ($count > 1) {
            $code = Result::FAILURE_IDENTITY_AMBIGUOUS;
            $messages[] = 'Username error';
        } elseif (! $this->pwHasher->verify($this->password, $user->password)) {
            $code = Result::FAILURE_CREDENTIAL_INVALID;
            $messages[] = 'Invalid password';
        } else {
            $code = Result::SUCCESS;
            $identity = new Identity($user->username, ['admin']);

            if ($this->pwHasher->needsRehash($user->password)) {
                $user->password = $this->pwHasher->hash($this->password);
                $user->save();
            }
        }

        return new Result($code, $identity, $messages);
    }
}
