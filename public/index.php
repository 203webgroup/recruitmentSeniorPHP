<?php

require __DIR__ . '/../vendor/autoload.php';

use Silex\Application;

$app['debug'] = true;
$app = new Silex\Application();

$app->get('/check_password/{password}/{minLength}', function($password, $minLength) {
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

$app->run();
