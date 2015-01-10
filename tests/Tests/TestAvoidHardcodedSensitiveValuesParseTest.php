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
            ['$username = "$somethingElse";', true],
            ['$user = "jorge";', false],
            ['$User = "giorgio";', false],
            ['$user = lookup("username");', true],
            ['$user = "$somethingElse";', true],
            ['$password = "safe123";', false],
            ['$Password = "password";', false],
            ['$password = lookup("password");', true],
            ['$password = "$somethingElse";', true],
            ['$pass = "notsafe";', false],
            ['$Pass = "abc123";', false],
            ['$pass = lookup("password");', true],
            ['$pass = "$somethingElse";', true],
            ['$pwd = "letmein";', false],
            ['$Pwd = "knocknock";', false],
            ['$PWD = "whiterabbit";', false],
            ['$pwd = lookup("password");', true],
            ['$PwD = "$somethingElse";', true],
            ];
    }

    protected function buildTest()
    {
        return new TestAvoidHardcodedSensitiveValues();
    }
}
