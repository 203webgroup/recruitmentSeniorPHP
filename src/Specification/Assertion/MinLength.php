<?php

namespace Specification\Assertion;

use Specification\Assertion\Exception\MinLength as MinLengthException;

class MinLength extends Template
{
    public function __construct($minLength)
    {
        $this->minLength = $minLength;
    }

    protected function doCheck($target)
    {
        $targetLength = mb_strlen(trim($target));
        if ($targetLength < $this->minLength) {
            throw new MinLengthException();
        }
    }
}
