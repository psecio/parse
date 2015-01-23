<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class ConsoleDotsTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');

        // The order of the calls to write should match the order of the events fired on $console
        $output->shouldReceive('write')->ordered()->once()->with(".");
        $output->shouldReceive('write')->ordered()->once()->with("<error>E</error>");
        $output->shouldReceive('write')->ordered()->once()->with("\n");
        $output->shouldReceive('write')->ordered()->once()->with("<error>I</error>");

        $console = new ConsoleDots($output);
        $console->setLineLength(2);

        $console->onScanStart();

        // Writes a dot as a file is scanned
        $console->onFileOpen(m::mock('\Psecio\Parse\Event\FileEvent'));
        $console->onFileClose();

        // Writes an E as an error occurs
        // Also triggers a new line as the line witdth is set to 2
        $console->onFileOpen(m::mock('\Psecio\Parse\Event\FileEvent'));
        $console->onFileError(m::mock('\Psecio\Parse\Event\ErrorEvent'));
        $console->onFileClose();

        // Writes an I as an issue occurs
        $console->onFileOpen(m::mock('\Psecio\Parse\Event\FileEvent'));
        $console->onFileIssue(m::mock('\Psecio\Parse\Event\IssueEvent'));
        $console->onFileClose();

        // Writes nothing
        $console->onDebug(m::mock('\Psecio\Parse\Event\MessageEvent'));

        $console->onScanComplete();
    }
}
