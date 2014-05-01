<?php

namespace Specification;

class Username extends Specification
{
    public function check($username, $minLength)
    {
        $this->assert('MinLength', [$username, $minLength])
            ->assert('AnySpecialCharacter', $username);

        return true;
    }
}
