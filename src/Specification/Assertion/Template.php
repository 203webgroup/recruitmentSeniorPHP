<?php

namespace Specification\Assertion;

abstract class Template implements Assertion
{
    final public function check($target)
    {
        $this->doCheck($target);
        return $target;
    }

    abstract protected function doCheck($target);
}
