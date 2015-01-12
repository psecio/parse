<?php

namespace Psecio\Parse\Rule;

class SystemFunctionsTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['exec();', false],
            ['passthru();', false],
            ['system();', false],
            ['$x = `ls -al`;', false],
            ['another_function();', true],
        ];
    }

    protected function buildTest()
    {
        return new SystemFunctions();
    }
}
