<?php

namespace Specification;

use Specification\Assertion\Exception\Assertion as AssertionException;
use Specification\Exception\InvalidUsername;
use DependencyInjection\Container;

class Username extends Specification
{
    public function check($username)
    {
        $minLength = $this->getMinLength();
        try {
            $this->assert('MinLength', [$username, $minLength])
                ->assert('AnySpecialCharacter', $username);
        } catch (AssertionException $ae) {
            throw new InvalidUsername($ae->getMessage());
        }

        return true;
    }

    private function getMinLength()
    {
        return Container::get('config')['specifications']['username']['min_length'];
    }
}
