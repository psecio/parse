<?php

namespace Psecio\Parse\Tests;

use Psecio\Parse\ParseTest;

class TestAvoidHardcodedSensitiveValues_ParseTest extends ParseTest
{
    public function parseSampleProvider()
    {
        return [
            ['$x = "123";', true],
            ['$username = "george";', false],
            ['$userName = "jorn";', false],
            ['$uSeRnAmE = "joren";', false],
            ['$username = lookup("username");', true],
            ['$user = "jorge";', false],
            ['$User = "giorgio";', false],
            ['$user = lookup("username");', true],
            ['$password = "safe123";', false],
            ['$Password = "password";', false],
            ['$password = lookup("password");', true],
            ['$pass = "notsafe";', false],
            ['$Pass = "abc123";', false],
            ['$pass = lookup("password");', true],
            ['$pwd = "letmein";', false],
            ['$Pwd = "knocknock";', false],
            ['$PWD = "whiterabbit";', false],
            ['$pwd = lookup("password");', true],
            ];
    }

    public function test_description()
    {
        $test = $this->buildTest();
        $this->assertStringMatchesFormat('%s', $test->getDescription());
    }

    protected function buildTest()
    {
        return new TestAvoidHardcodedSensitiveValues();
    }
}
