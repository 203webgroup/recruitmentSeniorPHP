<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\SomeSpecialCharacter as SomeSpecialCharacterException;
use Specification\Assertion\Exception\AnySpecialCharacter as AnySpecialCharacterException;

class AnySpecialCharacter implements Assertion
{
    public function check($target)
    {
        if ($this->hasSomeSpecialCharacter($target)) {
            throw new SomeSpecialCharacterException();
        }

        return true;
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
