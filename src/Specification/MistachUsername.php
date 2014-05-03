<?php

namespace Specification\MismatchUsername;

use Specification\Exception\MismatchUsername as MismatchUsernameException;

class MismatchUsername extends Specification
{
    public function __construct($username)
    {
        $this->username = $username;
    }

    public function check($confirmation)
    {
        if ($this->username !== $confirmation) {
            throw new MismatchUsernameException();
        }
    }
}
