<?php

namespace Psecio\Parse\Rule;

class ExitOrDieTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            // Bare exit should be ok.
            ['exit;', true],
            ['die;', true],

            // Exit with error code should be ok.
            ['exit(1);', true],
            ['die(1);', true],

            // Shouldn't exit with a string
            ['exit("message");', true],
            ['die("message");', true],

            // Shouldn't exit with a variable
            ['exit($e);', true],
            ['die($e);', true],

            // Something else
            ['exitOrNot("message");', true],
        ];
    }

    protected function buildTest()
    {
        return new ExitOrDie();
    }
}
