<?php

namespace Specification;

abstract class Specification
{
    protected function assert($assertionName, $params)
    {
        $assertionName = 'Specification\\Assertion\\' . $assertionName;
        $rf = new \ReflectionClass($assertionName);

        $assertion = $rf->newInstanceArgs((array) $params);
        $assertion->check();

        return $this;
    }
}
