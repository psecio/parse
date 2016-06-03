<?php

namespace Psecio\Parse\Rule;

class DisplayErrorsTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ["ini_set('display_errors', 0);",        true],
            ["ini_set('display_errors', '0');",      true],
            ["ini_set('display_errors', false);",    true],
            ["ini_set('display_errors', 'false');",  true],
            ["ini_set('display_errors', 'off');",    true],
            ["ini_set('display_errors', 'stderr');", true],
            ["ini_set('something-else', 1);",        true],
            ['ini_set($setting_name, $new_value);',  true],
            ["ini_set('display_errors', 1);",        false],
            ["ini_set('display_errors', '1');",      false],
            ["ini_set('display_errors', true);",     false],
            ["ini_set('display_errors', 'true');",   false],
            ["ini_set('display_errors', 'on');",     false],
            ["ini_set('display_errors');",           false],
        ];
    }

    protected function buildTest()
    {
        return new DisplayErrors;
    }
}
