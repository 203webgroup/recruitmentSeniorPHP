<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\SomeSpecialCharacter as SomeSpecialCharacterException;
use Specification\Assertion\Exception\AnySpecialCharacter as AnySpecialCharacterException;

class AnySpecialCharacter extends Template
{
    protected function doCheck($target)
    {
        if ($this->hasSomeSpecialCharacter($target)) {
            throw new SomeSpecialCharacterException();
        }
    }

    private function hasSomeSpecialCharacter($target)
    {
        $specialCharAssertion = new SomeSpecialCharacter();
        try {
            $specialCharAssertion->check($target);
        } catch (AnySpecialCharacterException $asc) {
            return false;
        }

        return true;
    }
}
