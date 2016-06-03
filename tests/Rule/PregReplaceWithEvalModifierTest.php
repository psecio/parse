<?php

namespace Psecio\Parse\Rule;

class PregReplaceWithEvalModifierTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['preg_replace("/test/e", "", "");', false],
            ['preg_replace("/test/ie", "", "");', false],
            ['preg_replace("/test/ei", "", "");', false],
            ['preg_replace("/test/eU", "", "");', false],
            ['preg_replace("#test#e", "", "");', false],
            ['preg_replace("/test/", "", "");', true],
            ['preg_replace("/test/i", "", "");', true],
            ['preg_replace("/test/E", "", "");', true],
            ['preg_replace("#/est#", "");', true],
            ['preg_match("/test/e", "");', true],
            ['preg_replace("/^".preg_quote(PROTOCOL.SERVER_NAME, "/")."/", FULL_PATH, $src);', true],
        ];
    }

    protected function buildTest()
    {
        return new PregReplaceWithEvalModifier;
    }
}
