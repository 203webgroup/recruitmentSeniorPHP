<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnySpecialCharacter as AnySpecialCharacterException;

class SomeSpecialCharacter implements Assertion
{
    public function check($target)
    {
        if (!preg_match('#@#', $target)) {
            throw new AnySpecialCharacterException();
        }

        return $this;
    }
}
