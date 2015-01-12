<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class BooleanIdentityTest extends ParseTest
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
        return new BooleanIdentity;
    }
}
