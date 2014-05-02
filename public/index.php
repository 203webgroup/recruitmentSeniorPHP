<?php

require __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app = new Application();

$configFile = __DIR__ . '/../config/config.json';
$dataFile = __DIR__ . '/../data/data.json';
$app['config'] = json_decode(file_get_contents($configFile), true);
$app['data'] = json_decode(file_get_contents($dataFile), true);

$app['user.credentials.repository'] = $app->share(
    function () use ($app) {
        return new Model\User\Credential\Repository($app['data']['credentials']);
    }
);

$app['user.controller'] = $app->share(
    function () use ($app) {
        return new Controller\User($app['user.credentials.repository']);
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
$app->get(
    '/user/{username}/update_password/{newPassword}',
    function ($username, $newPassword, Application $app) {
        return $app['user.controller']->updatePassword($username, $newPassword);
    }
);
$app->get(
    'user/{username}/update_username/{newUsername}',
    function ($username, $newUsername, Application $app) {
        return $app['user.controller']->updateUsername($username, $newUsername);
    }
);

$app->run();
