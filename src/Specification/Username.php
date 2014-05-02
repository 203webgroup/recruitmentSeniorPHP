<?php

namespace Specification;

use Specification\Assertion\Exception\Assertion as AssertionException;
use Specification\Exception\InvalidUsername;

class Username extends Specification
{
    public function __construct($minLength)
    {
        $this->minLength = $minLength;
    }

    public function check($username)
    {
        try {
            $this->assert('MinLength', [$username, $this->minLength])
                ->assert('AnySpecialCharacter', $username);
        } catch (AssertionException $ae) {
            throw new InvalidUsername($ae->getMessage());
        }

        return true;
    }
}
