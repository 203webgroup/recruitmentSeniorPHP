<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnyDigit as AnyDigitException;

class SomeDigit extends Template
{
    protected function doCheck($target)
    {
        if (!preg_match('#\d+#', $target)) {
            throw new AnyDigitException();
        }
    }
}
