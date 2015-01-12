<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class LogicalOperatorsTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$a && $b;', true],
            ['$a || $b;', true],
            ['$a and $b;', false],
            ['$a or $b;', false],
            ['$a xor $b;', false],
        ];
    }

    protected function buildTest()
    {
        return new LogicalOperators;
    }
}
