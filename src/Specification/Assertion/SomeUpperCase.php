<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\SomeUpperCase as SommeUpperCaseException;

class SomeUpperCase implements Assertion
{
    public function __construct($target)
    {
        $this->target = $target;
    }

    public function check()
    {
        if (!preg_match('#[A-Z]+#', $this->target)) {
            throw new SommeUpperCaseException();
        }

        return $this;
    }
}
