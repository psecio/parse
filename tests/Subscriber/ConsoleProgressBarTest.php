<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;

class ConsoleProgressBarTest extends \PHPUnit_Framework_TestCase
{
    public function testOutput()
    {
        // ProgressBar is final and cannot be mocked
        $bar = new ProgressBar(new NullOutput());

        $console = new ConsoleProgressBar($bar);
        $console->onScanStart();
        $console->onFileClose();
        $console->onScanComplete();
    }
}
