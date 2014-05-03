<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnySpecialCharacter as AnySpecialCharacterException;

class SomeSpecialCharacter extends Template
{
    protected function doCheck($target)
    {
        if (!preg_match('#@#', $target)) {
            throw new AnySpecialCharacterException();
        }
    }
}
