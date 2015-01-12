<?php

namespace Psecio\Parse\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Display a progress bar to visualize scan progression
 */
class ConsoleProgressBar implements EventSubscriberInterface
{
    use Helper\SubscriberTrait;

    /**
     * The progress bar format used of the number if steps is known
     */
    const FORMAT_STEPS_KNOWN = '%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%';

    /**
     * The progress bar format used of the number if steps is not known
     */
    const FORMAT_STEPS_UNKNOWN = '%current% [%bar%] %elapsed:6s% %memory:6s%';

    /**
     * @var ProgressBar The progress bar
     */
    private $progressBar;

    /**
     * Inject progress bar
     *
     * @param ProgressBar $progressBar
     */
    public function __construct(ProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;
        $this->progressBar->setFormat(
            $this->progressBar->getMaxSteps() ? self::FORMAT_STEPS_KNOWN : self::FORMAT_STEPS_UNKNOWN
        );
    }

    /**
     * Reset progress bar on scan start
     *
     * @return null
     */
    public function onScanStart()
    {
        $this->progressBar->start();
    }

    /**
     * Finish progress bar on scan complete
     *
     * @return null
     */
    public function onScanComplete()
    {
        $this->progressBar->finish();
    }

    /**
     * Advance progress bar on file close
     *
     * @return null
     */
    public function onFileClose()
    {
        $this->progressBar->advance();
    }
}
