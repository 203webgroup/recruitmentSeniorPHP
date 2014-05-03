<?php

namespace Tests\Specification;

use Tests\TestCase;
use Specification\Username as UsernameSpecification;
use DependencyInjection\Container as Dic;

class UsernameCheckerTest extends TestCase
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

        $this->sut = new UsernameSpecification();
    }

    public function tearDown()
    {
        $this->tearDownDic();
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
