<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class ConsoleReportTest extends \PHPUnit_Framework_TestCase
{
    public function testPassReport()
    {
        $report = new ConsoleReport(
            m::mock('\Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('writeln')
                ->once()
                ->with("\n\n<info>OK (2 files scanned)</info>")
                ->mock()
        );

        $report->onScanStart();
        $report->onFileOpen();
        $report->onFileOpen();
        $report->onScanComplete();
    }

    public function testFailureReport()
    {
        $expected = "

There was 1 error

<comment>1) /error/path</comment>
<error>error description</error>

There was 1 issue

<comment>1) /issue/path on line 1</comment>
issue description
<error>> php source</error>

<error>FAILURES!</error>
<error>Scanned: 0, Errors: 1, Issues: 1.</error>";

        $report = new ConsoleReport(
            m::mock('\Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('writeln')
                ->once()
                ->with($expected)
                ->mock()
        );

        $report->onScanStart();

        $messageEvent = m::mock('\Psecio\Parse\Event\MessageEvent');
        $messageEvent->shouldReceive('getMessage')->once()->andReturn('error description');
        $messageEvent->shouldReceive('getFile->getPath')->once()->andReturn('/error/path');

        $report->onFileError($messageEvent);

        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('getPath')->once()->andReturn('/issue/path');
        $file->shouldReceive('getLines')->once()->with('1')->andReturn(['php source']);

        $issueEvent = m::mock('\Psecio\Parse\Event\IssueEvent');
        $issueEvent->shouldReceive('getNode->getAttributes')->once()->andReturn(['startLine' => '1']);
        $issueEvent->shouldReceive('getTest->getDescription')->once()->andReturn('issue description');
        $issueEvent->shouldReceive('getFile')->zeroOrMoreTimes()->andReturn($file);

        $report->onFileIssue($issueEvent);

        $report->onScanComplete();
    }

    public function testSubscription()
    {
        $this->assertInternalType(
            'array',
            ConsoleReport::getSubscribedEvents()
        );
    }
}
