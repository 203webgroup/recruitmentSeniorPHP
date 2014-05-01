<?php

namespace Tests\Specification;

use Tests\TestCase;
use Specification\Username as UsernameSpecification;

class UsernameCheckerTest extends TestCase
{
    public function setUp()
    {
        $this->sut = new UsernameSpecification();
    }

    public function tearDown()
    {
        unset($this->sut);
    }

    /**
     * @expectedException Specification\Assertion\Exception\MinLength
     * @expectedExceptionMessage Minimal length
     */
    public function testInvalidUsernameByMinLength()
    {
        $this->sut->check('usern', 6);
    }

    /**
     * @expectedException Specification\Assertion\Exception\SomeSpecialCharacter
     * @expectedExceptionMessage Avoid special characters
     */
    public function testInvalidUsernameBySpecialChars()
    {
        $this->sut->check('usern@me', 6);
    }

    public function testValidUsername()
    {
        $this->assertTrue($this->sut->check('Username3', 6));
    }
}
