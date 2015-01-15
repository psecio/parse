<?php

namespace Psecio\Parse\Rule;

class EchoWithFileGetContentsTest extends RuleTestCase
{
    public function parseSampleProvider()
    {
        return [
            ['echo "" . file_get_contents("file.txt");', false],
            ['echo file_get_contents("file.txt") . "";', false],
            ['echo file_me_away();', true],
        ];
    }

    protected function buildTest()
    {
        return new EchoWithFileGetContents();
    }
}
