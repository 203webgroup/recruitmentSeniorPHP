<?php

namespace Tests\Specification;

use Tests\TestCase;
use Specification\Password as PasswordSpecification;
use DependencyInjection\Container as Dic;

class PasswordTest extends TestCase
{
    public function setUp()
    {
        $this->setUpDic();
        Dic::set(
            'config',
            [
                'specifications' => [
                    'password' => [
                        'min_length' => 6
                    ]
                ]
            ]
        );
        $this->sut = new PasswordSpecification();
    }

    public function tearDown()
    {
        $this->tearDownDic();
        unset($this->sut);
    }

    /**
     * @expectedException Specification\Exception\InvalidPassword
     * @expectedExceptionMessage Minimal length
     */
    public function testInvalidPasswordByMinLength()
    {
        $this->sut->check('n3wP@');
    }

    /**
     * @expectedException Specification\Exception\InvalidPassword
     * @expectedExceptionMessage Special characters missing
     */
    public function testInvalidPasswordBySpecialCharsMissing()
    {
        $this->sut->check('newPassword2');
    }

    /**
     * @expectedException Specification\Exception\InvalidPassword
     * @expectedExceptionMessage Upper case characters missing
     */
    public function testInvalidPasswordByUpperCaseCharMissing()
    {
        $this->sut->check('n3wp@ssw0rd');
    }

    /**
     * @expectedException Specification\Exception\InvalidPassword
     * @expectedExceptionMessage Digit characters missing
     */
    public function testInvalidPasswordByDigitCharMissing()
    {
        $this->sut->check('newP@ssword');
    }

    public function testValidPassword()
    {
        $this->assertTrue($this->sut->check('@PassWord3'));
    }
}
