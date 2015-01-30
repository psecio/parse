<?php

namespace Psecio\Parse\Subscriber\Console;

use Mockery as m;

class ProgressTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        $bar = m::mock('\Symfony\Component\Console\Helper\ProgressBar');

        // The order of the calls to write should match the order of the events fired on $console
        $bar->shouldReceive('getMaxSteps')->ordered()->once();
        $bar->shouldReceive('setFormat')->ordered()->once();
        $bar->shouldReceive('start')->ordered()->once();
        $bar->shouldReceive('advance')->ordered()->once();
        $bar->shouldReceive('finish')->ordered()->once();

        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');
        $output->shouldReceive('writeln')->once()->with("/Parse/");

        $console = new Progress($output, $bar);
        $console->onScanStart(
            m::mock('\Psecio\Parse\Event\MessageEvent')->shouldReceive('getMessage')->mock()
        );
        $console->onFileClose();
        $console->onScanComplete();
    }
}
