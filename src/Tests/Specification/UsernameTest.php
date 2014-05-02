<?php

namespace Tests\Specification;

use Tests\TestCase;
use Specification\Username as UsernameSpecification;

class UsernameCheckerTest extends TestCase
{
    public function setUp()
    {
        $this->sut = new UsernameSpecification($minLength = 6);
    }

    public function tearDown()
    {
        unset($this->sut);
    }

    /**
     * @expectedException Specification\Exception\InvalidUsername
     * @expectedExceptionMessage Minimal length
     */
    public function testInvalidUsernameByMinLength()
    {
        $this->sut->check('usern');
    }

    /**
     * @expectedException Specification\Exception\InvalidUsername
     * @expectedExceptionMessage Avoid special characters
     */
    public function testInvalidUsernameBySpecialChars()
    {
        $this->sut->check('usern@me');
    }

    public function testValidUsername()
    {
        $this->assertTrue($this->sut->check('Username3'));
    }
}
