<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestExtractNotOverwriteParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['extract($a);', false],
            ['extract();', false],
            ['extract($a, EXTR_OVERWRITE);', false],
            ['extract($a, EXTR_SKIP);', true],
            ['extract($a, EXTR_PREFIX_SAME);', true],
            ['extract($a, EXTR_PREFIX_ALL);', true],
            ['extract($a, EXTR_PREFIX_INVALID);', true],
            ['extract($a, EXTR_PREFIX_IF_EXISTS);', true],
            ];
    }

    protected function buildTest()
    {
        return new TestExtractNotOverwrite(false);
    }
}
