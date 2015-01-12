<?php

namespace Psecio\Parse\Rule;

class EvalFunctionTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['eval("asdfasdfasdf");', false],
            ['17 + eval("32 - 6");', false],
            ['17 + (32 - 6);', true],
        ];
    }

    protected function buildTest()
    {
        return new EvalFunction();
    }
}
