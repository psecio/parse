<?php

namespace Psecio\Parse\Rule;

class InArrayStrictTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['in_array(\'test\', $var);', false],
            ['in_array(\'test\', $var, true);', true],
            ['in_array(\'test\', $var, false);', false]
        ];
    }

    protected function buildTest()
    {
        return new InArrayStrict;
    }
}
