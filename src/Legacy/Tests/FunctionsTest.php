<?php

namespace MyTaste\Legacy\Tests;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider pickMiddlePartAndDecorateWithDotsProvider
     */
    public function testPickMiddlePartAndDecorateWithDots(
        $expected,
        $text,
        $starting,
        $ending,
        $length,
        $expectedReturn = null
    ) {
        $this->assertEquals(
            $expectedReturn,
            pickMiddlePartAndDecorateWithDots($text, $length, $starting, $ending)
        );
        $this->assertEquals($expected, $text);
    }

    public function pickMiddlePartAndDecorateWithDotsProvider()
    {
        return [
            ['....', 'ola k ase', 'ola', 'ase', 0],
            ['..a k..', 'ola k ase', 'ola', 'ase', 3],
            ['..ola k ..', 'ola k ase', 'ola', 'ase', 6],
            ['.. ..', 'ola k ase', 'ola', 'ase', 1],

            ['ola k ase', 'ola k ase', 'ola', 'ase', 9],
            ['..a k ase t..', 'ola k ase tu', ' ', ' ', 9],

            ['..k as..', 'ola k ase', 'ola', 'ase', -1],
            ['   ', '   ', '', ' ', 2, false],
            ['ola k ..', 'ola k ase', 'ola', 'the fuck', 6],
            ['..ola k ..', 'ola k ase', 'what', 'ase', 6],
            ['ola k ..', 'ola k ase', 'what', 'the fuck', 6],
        ];
    }
}