<?php

namespace Psecio\Parse\Rule;

class OutputWithVariableTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['echo "test string" . "\n";', true],
            ['echo "test string" . $a;', false]
        ];
    }

    protected function buildTest()
    {
        return new OutputWithVariable;
    }
}
