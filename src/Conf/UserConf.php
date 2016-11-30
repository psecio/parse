<?php

namespace Psecio\Parse\Conf;

use Symfony\Component\Console\Input\InputInterface;

/**
 * User specified command line configurations
 */
class UserConf implements Configuration
{
    /**
     * @var InputInterface Input object
     */
    private $input;

    /**
     * Load user input
     *
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    public function getFormat()
    {
        return $this->input->getOption('format');
    }

    public function getPaths()
    {
        return $this->input->getArgument('path');
    }

    public function getIgnorePaths()
    {
        return $this->parseCsv($this->input->getOption('ignore-paths'));
    }

    public function getExtensions()
    {
        return $this->parseCsv($this->input->getOption('extensions'));
    }

    public function getRuleWhitelist()
    {
        return $this->parseCsv($this->input->getOption('whitelist-rules'));
    }

    public function getRuleBlacklist()
    {
        return $this->parseCsv($this->input->getOption('blacklist-rules'));
    }

    public function disableAnnotations()
    {
        return $this->input->getOption('disable-annotations');
    }

    /**
     * Parse comma-separated values from string
     *
     * Using array_filter ensures that an empty array is returned when an empty
     * string is parsed.
     *
     * @param  string $string
     * @return array
     */
    private function parseCsv($string)
    {
        return array_filter(explode(',', $string));
    }
}
