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
            ['$user_name = "george";', false],
            ['$uSeR_nAmE = "joren";', false],
            ['$user_name = lookup("username");', true],
            ['$user_name = "$somethingElse";', true],
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
            ['$pswd = "password";', false],
            ['$Pswd = "password";', false],
            ['$PsWd = lookup("password");', true],
            ['$pswd = "$somethingElse";', true],
            ['$awskey = "a4jgm5mgfjvmajrjf";', false],
            ['$AwsKey = "j4jfjkj5kfjrajrk4";', false],
            ['$awskey = lookup("AWS_KEY");', true],
            ['$awskey = $somethingElse;', true],
            ['$aws_key = "a4jgm5mgfjvmajrjf";', false],
            ['$Aws_Key = "j4jfjkj5kfjrajrk4";', false],
            ['$aws_key = lookup("awskey");', true],
            ['$aws_key = $somethingElse;', true],
            ['$awssecret = "asdfasdfjkjk134j3";', false],
            ['$awsSecret = "asdfasdfjkjk134j3";', false],
            ['$awssecret = lookup("secret");', true],
            ['$awssecret = $somethingElse;', true],
            ['$aws_secret = "asdfasdfjkjk134j3";', false],
            ['$aws_Secret = "asdfasdfjkjk134j3";', false],
            ['$aws_secret = lookup("secret");', true],
            ['$aws_secret = $somethingElse;', true],
            ['$othersecret = "asdfasdfjkjk134j3";', false],
            ['$otherSecret = "asdfasdfjkjk134j3";', false],
            ['$othersecret = lookup("secret");', true],
            ['$othersecret = $somethingElse;', true],
            ['$other_secret = "asdfasdfjkjk134j3";', false],
            ['$other_Secret = "asdfasdfjkjk134j3";', false],
            ['$other_secret = lookup("secret");', true],
            ['$other_secret = $somethingElse;', true],
            ['$super_secret_secret = "really secret";', false],
            ];
    }

    protected function buildTest()
    {
        return new TestAvoidHardcodedSensitiveValues();
    }
}
