<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Helper to simplify writing to the console
 */
trait OutputTrait
{
    /**
     * @var OutputInterface Registered output
     */
    protected $output;

    /**
     * Register output interface
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Write to console
     *
     * @param  mixed  ...$arg Any number of sprintf arguments
     * @return void
     */
    protected function write()
    {
        $this->output->write(call_user_func_array('sprintf', func_get_args()));
    }
}
