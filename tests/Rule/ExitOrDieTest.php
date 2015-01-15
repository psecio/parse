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
            ['exit("message");', false],
            ['die("message");', false],

            // Shouldn't exit with a variable
            ['exit($e);', false],
            ['die($e);', false],

            // Something else
            ['exitOrNot("message");', true],
        ];
    }

    protected function buildTest()
    {
        return new ExitOrDie();
    }
}
