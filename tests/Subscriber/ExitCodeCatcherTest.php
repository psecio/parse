<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class ExitCodeCatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessCode()
    {
        $this->assertSame(
            0,
            (new ExitCodeCatcher)->getExitCode()
        );
    }

    public function testErrorcodeOnIssue()
    {
        $exitCode = new ExitCodeCatcher;
        $exitCode->onFileIssue(m::mock('\Psecio\Parse\Event\IssueEvent'));
        $this->assertSame(1, $exitCode->getExitCode());
    }

    public function testErrorcodeOnError()
    {
        $exitCode = new ExitCodeCatcher;
        $exitCode->onFileError(m::mock('\Psecio\Parse\Event\ErrorEvent'));
        $this->assertSame(1, $exitCode->getExitCode());
    }
}
