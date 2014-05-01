<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnyUpperCase as AnyUpperCaseException;

class SomeUpperCase implements Assertion
{
    public function check($target)
    {
        if (!preg_match('#[A-Z]+#', $target)) {
            throw new AnyUpperCaseException();
        }

        return $this;
    }
}
