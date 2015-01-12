<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class DisplayErrorsTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ["ini_set('display_errors', 0);", true],
            ["ini_set('display_errors', '0');", true],
            ["ini_set('display_errors', false);", true],
            ["ini_set('display_errors', 'false');", true],
            ["ini_set('display_errors', 'off');", true],
            ["ini_set('display_errors', 'stderr');", true],
            ["ini_set('something-else', 1);", true],
            ["ini_set('display_errors', 1);", false],
            ["ini_set('display_errors', '1');", false],
            ["ini_set('display_errors', true);", false],
            ["ini_set('display_errors', 'true');", false],
            ["ini_set('display_errors', 'on');", false],
        ];
    }

    protected function buildTest()
    {
        return new DisplayErrors;
    }
}
