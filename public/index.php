<?php

require __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

$configFile = __DIR__ . '/../config/config.json';
$app['config'] = json_decode(file_get_contents($configFile), true);

$app['user.controller'] = $app->share(
    function () {
        return new Controller\User();
    }
);

$app->get(
    '/user/check_password/{password}',
    function ($password, Application $app) {
        return $app['user.controller']->checkPassword(
            $password,
            $app['config']['specifications']['password']['min_length']
        );
    }
);
$app->get(
    '/user/check_username/{username}',
    function ($username, Application $app) {
        return $app['user.controller']->checkUsername(
            $username,
            $app['config']['specifications']['username']['min_length']
        );
    }
);

$app->run();
