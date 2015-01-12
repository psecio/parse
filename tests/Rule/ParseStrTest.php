<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class ParseStrTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['parse_str();', false],
            ['mb_parse_str();', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new ParseStr();
    }
}
