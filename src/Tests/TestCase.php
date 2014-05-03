<?php

namespace Tests;

use Silex\Application;
use DependencyInjection\Container;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUpDic()
    {
        $container = new Application();
        Container::setRealContainer($container);
    }

    protected function tearDownDic()
    {
        Container::setRealContainer(null);
    }
}
