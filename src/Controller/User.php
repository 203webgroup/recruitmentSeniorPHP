<?php

namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Specification\Password as PasswordSpecification;

class User
{
    public function checkPassword($password, $minLength)
    {
        $pwdSpec = new \Specification\Password();

        try {
            $pwdSpec->check($password, $minLength);
        } catch (\DomainException $e) {
            $error = [
                'error_msg' => sprintf('Error: %s', $e->getMessage())
            ];
            return new JsonResponse($error);
        }

        return new JsonResponse('Valid password');
    }

    public function checkUsername($username, $minLength)
    {
        $checker = new \Specification\Username();

        try {
            $checker->check($username, $minLength);
        } catch (\DomainException $e) {
            $error = [
                'error_msg' => sprintf('Error: %s', $e->getMessage())
            ];
            return new JsonResponse($error);
        }

        return new JsonResponse('Valid password');
    }
}
