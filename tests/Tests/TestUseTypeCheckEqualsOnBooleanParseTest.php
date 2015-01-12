<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestUseTypeCheckEqualsOnBooleanParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$var === true;', true],
            ['false === $var;', true],
            ['$var == $var;', true],
            ['true == $var;', false],
            ['false == $var;', false],
            ['$var == true;', false],
            ['$var == false;', false],
        ];
    }

    protected function buildTest()
    {
        return new TestUseTypeCheckEqualsOnBoolean;
    }
}
