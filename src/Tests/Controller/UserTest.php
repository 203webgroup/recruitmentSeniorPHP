<?php

namespace Tests\Controller;

use Tests\TestCase;
use Controller\User;

class UserTest extends TestCase
{
    public function setUp()
    {
        $this->credentialMock = $this->getMockBuilder('Model\\User\\Credential\\Credential')
            ->disableOriginalConstructor()
            ->getMock();
        $this->credentialsRepoMock = $this->getMock('Model\\User\\Credential\\Repository');
        $this->sut = new User($this->credentialsRepoMock);
    }

    public function tearDown()
    {
        unset($this->credentialMock);
        unset($this->credentialsRepoMock);
        unset($this->sut);
    }

    public function testUpdatePasswordWithValidPassword()
    {
        $username = 'avalidusername';
        $password = 'n3wP@ssw0rd';

        $this->credentialsRepoMock
            ->expects($this->once())
            ->method('getByUsername')
            ->with($username)
            ->willReturn($this->credentialMock);
        $this->credentialMock
            ->expects($this->once())
            ->method('setPassword')
            ->with($password);
        $this->credentialsRepoMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->credentialMock);

        $this->sut->updatePassword($username, $password);
    }
}
