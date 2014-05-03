<?php

namespace Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Specification\Password as PasswordSpecification;
use Specification\Exception\InvalidPassword as InvalidPasswordException;
use Specification\Username as UsernameSpecification;
use Specification\Exception\InvalidUsername as InvalidUsernameException;
use Model\User\Credential\Repository as CredentialRepository;

class User
{
    public function __construct(CredentialRepository $credentialsRepo)
    {
        $this->credentialsRepo = $credentialsRepo;
    }

    public function checkPassword($password)
    {
        $pwdSpec = new PasswordSpecification();

        try {
            $pwdSpec->check($password);
        } catch (InvalidPasswordException $ipe) {
            return $this->errorMessage($ipe->getMessage());
        }

        return new JsonResponse('Valid password');
    }

    public function checkUsername($username)
    {
        try {
            $this->checkIfValidUsername($username);
        } catch (InvalidUsernameException $e) {
            return $this->errorMessage($e->getMessage());
        }

        return new JsonResponse('Valid password');
    }

    private function checkIfValidUsername($username)
    {
        $spec = new UsernameSpecification();
        $spec->check($username);
    }

    public function updatePassword($username, $newPassword)
    {
        $credential = $this->credentialsRepo->getByUsername($username);
        if (!$credential) {
            return $this->errorMessage('User not found');
        }

        $credential->setPassword($newPassword);
        $this->credentialsRepo->persist($credential);

        return new JsonResponse('Password was changed successfully');
    }

    public function updateUsername($username, $newUsername, $newUsernameConfimation)
    {
        if ($newUsername !== $newUsernameConfimation) {
            return $this->errorMessage('username mismatch');
        }
        try {
            $this->checkIfValidUsername($newUsername);
        } catch (InvalidUsernameException $iue) {
            return $this->errorMessage($iue->getMessage());
        }

        if ($this->credentialsRepo->getByUsername($newUsername)) {
            return $this->errorMessage('not unique');
        }

        $credential = $this->credentialsRepo->getByUsername($username);
        $credential->setUsername($newUsername);
        $this->credentialsRepo->persist($credential);

        return new JsonResponse('Username was changed successfully');
    }

    private function errorMessage($errorMessage)
    {
        $error = [
            'error_msg' => sprintf('Error: %s', $errorMessage)
        ];
        return new JsonResponse($error);
    }
}
