<?php

namespace Tests\Specification\Assertion;

use Tests\TestCase;
use Specification\Assertion\MinLength;

class MinLengthTest extends TestCase
{
    /**
     * @dataProvider checkOkProvider
     */
    public function testOkCheck($minLength, $lengthOk)
    {
        $this->sut = new MinLength($minLength);
        $this->assertEquals(
            $lengthOk,
            $this->sut->check($lengthOk)
        );
    }

    public function checkOkProvider()
    {
        return [
            [3, 'asd']
        ];
    }
}
