<?php

namespace Tests\Controller;

use Tests\TestCase;
use Controller\User;
use DependencyInjection\Container as Dic;

class UserTest extends TestCase
{
    public function setUp()
    {
        $this->setUpDic();
        Dic::set(
            'config',
            [
                'specifications' => [
                    'username' => [
                        'min_length' => 6
                    ]
                ]
            ]
        );
        $this->credentialMock = $this->getMockBuilder('Model\\User\\Credential\\Credential')
            ->disableOriginalConstructor()
            ->getMock();
        $this->credentialsRepoMock = $this->getMock('Model\\User\\Credential\\Repository');
        $this->sut = new User($this->credentialsRepoMock);
    }

    public function tearDown()
    {
        $this->tearDownDic();
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

    public function testUpdateUsernameWithValidUsername()
    {
        $username = 'xavier';
        $newUsername = 'newUsername';

        $this->credentialsRepoMock
            ->expects($this->at(0))
            ->method('getByUsername')
            ->with($newUsername)
            ->willReturn(null);
        $this->credentialsRepoMock
            ->expects($this->at(1))
            ->method('getByUsername')
            ->with($username)
            ->willReturn($this->credentialMock);
        $this->credentialMock
            ->expects($this->once())
            ->method('setUsername')
            ->with($newUsername);
        $this->credentialsRepoMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->credentialMock);

        $this->sut->updateUsername($username, $newUsername, $newUsername);
    }

    public function testMinLengthError()
    {
        $response = $this->sut->checkUsername('Aa@3');
        $this->assertRegExp(
            '#"error_msg":"Error: Minimal length"#',
            (string) $response
        );
    }
}
