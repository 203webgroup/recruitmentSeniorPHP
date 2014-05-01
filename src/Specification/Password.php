<?php

namespace Specification;

class Password extends Specification
{
    public function check($password, $minLength)
    {
        $this->assert('MinLength', [$password, $minLength])
            ->assert('SomeSpecialCharacter', $password)
            ->assert('SomeUpperCase', $password)
            ->assert('SomeDigit', $password);

        return true;
    }
}
