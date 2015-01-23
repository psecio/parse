<?php

namespace Psecio\Parse\Rule;

class PregReplaceWithEvalModifierTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['preg_replace("/test/e", "", "");', false],
            ['preg_replace("/test/", "", "");', true],
            ['preg_replace("#/est#", "");', true],
            ['preg_match("/test/e", "");', true],
        ];
    }

    protected function buildTest()
    {
        return new PregReplaceWithEvalModifier;
    }
}
