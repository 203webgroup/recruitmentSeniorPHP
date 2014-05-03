<?php

namespace Tests\Model\User\Credential;

use Tests\TestCase;
use Model\User\Credential\Credential;
use Specification\Username as UsernameSpecification;
use DependencyInjection\Container;

class CredentialTest extends TestCase
{
    /**
     * @expectedException Specification\Exception\InvalidUserName
     */
    public function testSetInvalidUsername()
    {
        Container::set(
            'config',
            [
                'specifications' => [
                    'username' => [
                        'min_length' => 6
                    ]
                ]
            ]
        );

        $sut = new Credential(new UsernameSpecification());
        $sut->setUsername('ol@');
    }
}
