<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class ConsoleDebugTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');

        // The order of the calls to write should match the order of the events fired on $console
        $output->shouldReceive('write')->ordered()->once()->with("<comment>[DEBUG] Starting scan</comment>\n");
        $output->shouldReceive('write')->ordered()->once()->with("<comment>[DEBUG] debug message</comment>\n");
        $output->shouldReceive('write')->ordered()->once()->with("/\[DEBUG\] Scan completed in \d+\.\d+ seconds/");

        // Data for debug event
        $messageEvent = m::mock('\Psecio\Parse\Event\MessageEvent');
        $messageEvent->shouldReceive('getMessage')->andReturn('debug message');

        $console = new ConsoleDebug($output);

        // Should write debug start
        $console->onScanStart();

        // Writes debug message
        $console->onDebug($messageEvent);

        // Writes time used an extra new line
        $console->onScanComplete();
    }
}
