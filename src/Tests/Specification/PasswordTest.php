<?php

namespace Tests\Specification;

use Tests\TestCase;
use Specification\Password as PasswordSpecification;

class PasswordCheckerTest extends TestCase
{
    public function setUp()
    {
        $this->sut = new PasswordSpecification();
    }

    public function tearDown()
    {
        unset($this->sut);
    }

    /**
     * @expectedException Specification\Assertion\Exception\MinLength
     * @expectedExceptionMessage Minimal length
     */
    public function testInvalidPasswordByMinLength()
    {
        $this->sut->check('n3wP@', 6);
    }

    /**
     * @expectedException Specification\Assertion\Exception\SomeSpecialCharacter
     * @expectedExceptionMessage Special characters missing
     */
    public function testInvalidPasswordBySpecialCharsMissing()
    {
        $this->sut->check('newPassword2', 6);
    }

    /**
     * @expectedException Specification\Assertion\Exception\SomeUpperCase
     * @expectedExceptionMessage Upper case characters missing
     */
    public function testInvalidPasswordByUpperCaseCharMissing()
    {
        $this->sut->check('n3wp@ssw0rd', 6);
    }

    /**
     * @expectedException Specification\Assertion\Exception\SomeDigit
     * @expectedExceptionMessage Digit characters missing
     */
    public function testInvalidPasswordByDigitCharMissing()
    {
        $this->sut->check('newP@ssword', 6);
    }

    public function testValidPassword()
    {
        $this->assertTrue($this->sut->check('@PassWord3', 6));
    }
}
