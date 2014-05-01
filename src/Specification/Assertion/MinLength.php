<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\MinLength as MinLengthException;

class MinLength
{
    public function __construct($target, $minLength)
    {
        $this->targetLength = mb_strlen(trim($target));
        $this->minLength = $minLength;
    }

    public function check()
    {
        if ($this->targetLength < $this->minLength) {
            throw new MinLengthException();
        }

        return $this;
    }
}
