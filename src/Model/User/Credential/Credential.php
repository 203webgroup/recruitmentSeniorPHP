<?php

namespace Model\User\Credential;

use Specification\Username as UsernameSpecification;

class Credential
{
    private $username;
    private $password;

    public function __construct(UsernameSpecification $usernameSpec = null)
    {
        if (is_null($usernameSpec)) {
            $usernameSpec = new UsernameSpecification();
        }
        $this->usernameSpec = $usernameSpec;
    }

    public function setUsername($username)
    {
        $this->usernameSpec->check($username);
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
