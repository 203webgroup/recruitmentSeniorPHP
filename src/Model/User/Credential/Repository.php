<?php

namespace Model\User\Credential;

use Model\Repository as BaseRepository;
use DependencyInjection\Container;

class Repository extends BaseRepository
{
    public function getByUsername($username)
    {
        $credentials = Container::get('data')['credentials'];
        foreach ($credentials as $data) {
            if ($data['username'] == $username) {
                $credential = new Credential();
                $credential->setUsername($username);

                return $credential;
            }
        }

        return null;
    }

    public function persist(Credential $cred)
    {
        return true;
    }
}
