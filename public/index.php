<?php

require __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app['debug'] = true;

$app = new Application();

$app->get('/check_password/{password}/{minLength}', function($password, $minLength, Application $app) {
    $checker = new Specification\Password();

    try {
        $checker->check($password, $minLength);
    } catch (DomainException $e) {
        $error = [
            'error_msg' => sprintf('Error: %s', $e->getMessage())
        ];
        return $app->json($error);
    }

    return $app->json('Valid password');
});


$app->get('/check_username/{username}/{minLength}', function($username, $minLength, Application $app) {
    $checker = new Specification\Username();

    try {
        $checker->check($username, $minLength);
    } catch (DomainException $e) {
        $error = [
            'error_msg' => sprintf('Error: %s', $e->getMessage())
        ];
        return $app->json($error);
    }

    return $app->json('Valid password');
});

$app->run();
