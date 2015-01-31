<?php

namespace Psecio\Parse\Subscriber\Console;

use Psecio\Parse\Subscriber\BaseSubscriber;
use Psecio\Parse\Subscriber\OutputTrait;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Print header at construct
 */
class Header extends BaseSubscriber
{
    use OutputTrait;

    /**
     * Print header
     */
    public function __construct(OutputInterface $output)
    {
        $output->writeln("<info>Parse: A PHP Security Scanner</info>\n");
        $this->setOutput($output);
    }
}
