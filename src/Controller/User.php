<?php

namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Specification\Password as PasswordSpecification;
use Model\User\Credential\Repository as CredentialRepository;

class User
{
    public function __construct(CredentialRepository $credentialsRepo)
    {
        $this->credentialsRepo = $credentialsRepo;
    }

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

    public function updatePassword($username, $newPassword)
    {
        $credential = $this->credentialsRepo->getByUsername($username);
        $credential->setPassword($newPassword);
        $this->credentialsRepo->persist($credential);

        return new JsonResponse('Password was changed successfully');
    }

    public function updateUsername($username, $newUsername)
    {
        $credential = $this->credentialsRepo->getByUsername($username);
        $credential->setUsername($newUsername);
        $this->credentialsRepo->persist($credential);

        return new JsonResponse('Username was changed successfully');
    }
}
