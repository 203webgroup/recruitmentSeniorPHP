<?php

namespace Specification;

use Specification\Assertion\Exception\Assertion as AssertionException;
use Specification\Exception\InvalidPassword;

class Password extends Specification
{
    public function check($password, $minLength)
    {
        try {
            $this->assert('MinLength', [$password, $minLength])
                ->assert('SomeSpecialCharacter', $password)
                ->assert('SomeUpperCase', $password)
                ->assert('SomeDigit', $password);
        } catch (AssertionException $ae) {
            throw new InvalidPassword($ae->getMessage());
        }

        return true;
    }
}
