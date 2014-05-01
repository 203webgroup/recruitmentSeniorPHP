<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\AnyDigit as AnyDigitException;

class SomeDigit implements Assertion
{
    public function check($target)
    {
        if (!preg_match('#\d+#', $target)) {
            throw new AnyDigitException();
        }

        return $this;
    }
}
