<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\SomeSpecialCharacter as SomeSpecialCharacterException;

class SomeSpecialCharacter implements Assertion
{
    public function __construct($target)
    {
        $this->target = $target;
    }

    public function check()
    {
        if (!preg_match('#@#', $this->target)) {
            throw new SomeSpecialCharacterException("Special characters missing");
        }

        return $this;
    }
}
