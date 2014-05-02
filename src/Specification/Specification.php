<?php

namespace Specification;

abstract class Specification
{
    protected $assertionsNamespace = 'Specification\\Assertion\\';

    protected function assert($assertionClassName, $params)
    {
        $params = (array) $params;
        $target = array_shift($params);
        $constructorParam = array_shift($params);
        $assertionClassName = $this->assertionsNamespace . $assertionClassName;

        $assertion = new $assertionClassName($constructorParam);
        $assertion->check($target);

        return $this;
    }

    abstract public function check($target);
}
