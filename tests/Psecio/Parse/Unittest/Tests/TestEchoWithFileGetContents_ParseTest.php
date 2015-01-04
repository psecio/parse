<?php

namespace Psecio\Parse\Unittest\Tests;

use Psecio\Parse\Unittest\ParseTest;
use Psecio\Parse\Tests\TestEchoWithFileGetContents;

class TestEchoWithFileGetContents_ParseTest extends ParseTest
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
        return new TestEchoWithFileGetContents(false);
    }
}
