<?php

namespace Tests;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class DbTestCase extends TestCase
{
    protected static $app;

    public static function setUpBeforeClass()
    {
        $app = new Application();
        $app->register(
            new DoctrineServiceProvider(),
            [
                'db.options' => [
                    'driver' => 'pdo_sqlite',
                    'path' => static::getDataTestPath('data.db')
                ]
            ]);

        static::$app = $app;
    }

    public static function tearDownAfterClass()
    {
        static::$app = null;
    }

    protected function getApp()
    {
        return static::$app;
    }

    protected static function getDataTestPath($file = '')
    {
        $basePath = __DIR__ . '/data/';
        return $basePath . $file;
    }

    protected function getDbConnection()
    {
        return $this->getApp()['db'];
    }
}