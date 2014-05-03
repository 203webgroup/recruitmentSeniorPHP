<?php

namespace Specification;

use Specification\Assertion\Exception\Assertion as AssertionException;
use Specification\Exception\InvalidPassword;
use DependencyInjection\Container;

class Password extends Specification
{
    public function check($password)
    {
        $minLength = $this->getMinLength();
        try {
            $this->assert('MinLength', [$password, $minLength])
                ->assert('SomeSpecialCharacter', $password)
                ->assert('SomeUpperCase', $password)
                ->assert('SomeDigit', $password);
        } catch (AssertionException $ae) {
            throw new InvalidPassword($ae->getMessage());
        }

        return true;
    }

    private function getMinLength()
    {
        // print_r(Container::get('config'));die;
        return Container::get('config')['specifications']['password']['min_length'];
    }
}
