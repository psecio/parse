<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class ConsoleLinesTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');

        // The order of the calls to write should match the order of the events fired on $console
        $output->shouldReceive('write')->ordered()->once()->with("[PARSE] /path/to/file\n");
        $output->shouldReceive('write')->ordered()->once()->with("[PARSE] /path/to/file\n");
        $output->shouldReceive('write')->ordered()->once()->with("<error>[ERROR] message in /path/to/file</error>\n");
        $output->shouldReceive('write')->ordered()->once()->with("[PARSE] /path/to/file\n");
        $output->shouldReceive('write')->ordered()->once()->with("<error>[ISSUE] [Rule] On line 1 in path</error>\n");

        // Data for [PARSE] lines
        $fileEvent = m::mock('\Psecio\Parse\Event\FileEvent');
        $fileEvent->shouldReceive('getFile->getPath')->andReturn('/path/to/file');

        // Data for [ERROR] line
        $errorEvent = m::mock('\Psecio\Parse\Event\ErrorEvent');
        $errorEvent->shouldReceive('getMessage')->andReturn('message');
        $errorEvent->shouldReceive('getFile->getPath')->andReturn('/path/to/file');

        // Data for [ISSUE] line
        $issueEvent = m::mock('\Psecio\Parse\Event\IssueEvent');
        $issueEvent->shouldReceive('getNode->getLine')->andReturn(1);
        $issueEvent->shouldReceive('getRule->getName')->andReturn('Rule');
        $issueEvent->shouldReceive('getFile->getPath')->andReturn('path');

        $console = new ConsoleLines($output);

        $console->onScanStart();

        // File open writes [PARSE] line
        $console->onFileOpen($fileEvent);
        $console->onFileClose();

        // Writes [PARSE] and [ERROR] lines
        $console->onFileOpen($fileEvent);
        $console->onFileError($errorEvent);
        $console->onFileClose();

        // Writes [PARSE] and [ISSUE] lines
        $console->onFileOpen($fileEvent);
        $console->onFileIssue($issueEvent);
        $console->onFileClose();

        // Writes nothing
        $console->onDebug(m::mock('\Psecio\Parse\Event\MessageEvent'));

        $console->onScanComplete();
    }
}
