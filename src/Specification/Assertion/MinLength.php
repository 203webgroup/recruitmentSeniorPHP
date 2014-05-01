<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\MinLength as MinLengthException;

class MinLength implements Assertion
{
    public function __construct($minLength)
    {
        $this->minLength = $minLength;
    }

    public function check($target)
    {
        $targetLength = mb_strlen(trim($target));
        if ($targetLength < $this->minLength) {
            throw new MinLengthException();
        }

        return $this;
    }
}
