<?php

namespace Psecio\Parse\Rule;

class SessionRegenerateIdTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['session_regenerate_id();',       false],
            ['session_regenerate_id(false);',  false],
            ['session_regenerate_id(17);',     false],
            ['session_regenerate_id(true);',   true],
            ['$session_regenerate_id();',      true],
            ['$x["session_regenerate_id"]();', true],
            ['random_function();',             true],
        ];
    }

    protected function buildTest()
    {
        return new SessionRegenerateId();
    }
}
