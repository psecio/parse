<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\ParseTest;

class EchoWithFileGetContentsTest extends ParseTest
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
