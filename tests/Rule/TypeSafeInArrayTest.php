<?php

namespace Psecio\Parse\Rule;

class TypeSafeInArrayTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['in_array("test", ["foo"]);', false],
            ['in_array("test", ["foo"], true);', true],
            ['in_array("test", ["foo"], false);', false]
        ];
    }

    protected function buildTest()
    {
        return new TypeSafeInArray();
    }
}
