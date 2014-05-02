<?php

namespace Model\User\Credential;

use Model\Repository as BaseRepository;

class Repository extends BaseRepository
{
    public function getByUsername($username)
    {
        $credential = new Credential();
        $credential->setUsername($username);

        return $credential;
    }

    public function persist(Credential $c)
    {
        return true;
    }
}