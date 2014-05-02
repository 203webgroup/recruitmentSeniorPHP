<?php

namespace Tests\Model\User\Credential;

use Tests\TestCase;
use Model\User\Credential\Credential;
use Specification\Username;

class CredentialTest extends TestCase
{
    public function setUp()
    {
        $this->sut = new Credential(new Username($minLength = 6));
    }

    public function tearDown()
    {
        unset($this->sut);
    }

    /**
     * @expectedException Specification\Exception\InvalidUserName
     */
    public function testSetInvalidUsername()
    {
        $this->sut->setUsername('ol@');
    }
}
