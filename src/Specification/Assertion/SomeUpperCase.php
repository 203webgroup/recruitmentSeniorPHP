<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnyUpperCase as AnyUpperCaseException;

class SomeUpperCase extends Template
{
    protected function doCheck($target)
    {
        if (!preg_match('#[A-Z]+#', $target)) {
            throw new AnyUpperCaseException();
        }
    }
}
