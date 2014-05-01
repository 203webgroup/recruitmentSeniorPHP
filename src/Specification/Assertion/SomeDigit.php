<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\SomeDigit as SomeDigitException;

class SomeDigit
{
    public function __construct($target)
    {
        $this->target = $target;
    }

    public function check()
    {
        if (!preg_match('#\d+#', $this->target)) {
            throw new SomeDigitException();
        }

        return $this;
    }
}
