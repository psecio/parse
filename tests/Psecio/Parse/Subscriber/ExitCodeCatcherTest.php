<?php

namespace Psecio\Parse\Subscriber;

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
        $exitCode->onFileIssue();
        $this->assertSame(1, $exitCode->getExitCode());
    }

    public function testErrorcodeOnError()
    {
        $exitCode = new ExitCodeCatcher;
        $exitCode->onFileError();
        $this->assertSame(1, $exitCode->getExitCode());
    }

    public function testSubscription()
    {
        $this->assertInternalType(
            'array',
            ExitCodeCatcher::getSubscribedEvents()
        );
    }
}
