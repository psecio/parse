<?php

namespace Psecio\Parse\Conf;

/**
 * Read configurations from dual sources
 */
class DualConf implements Configuration
{
    /**
     * @var Configuration Primary configurations
     */
    private $primary;

    /**
     * @var Configuration Secondary configurations
     */
    private $secondary;

    /**
     * Load configurations
     *
     * Primary configurations take precedence if specified
     *
     * @param Configuration      $primary
     * @param Configuration|null $secondary
     */
    public function __construct(Configuration $primary, Configuration $secondary = null)
    {
        $this->primary = $primary;
        $this->secondary = $secondary ?: new DefaultConf;
    }

    /**
     * Get output format identifier
     *
     * @return string
     */
    public function getFormat()
    {
        return strtolower($this->primary->getFormat() ?: $this->secondary->getFormat());
    }

    /**
     * Get list of paths to scan
     *
     * @return string[]
     */
    public function getPaths()
    {
        return $this->primary->getPaths() ?: $this->secondary->getPaths();
    }

    /**
     * Get list of paths to ignore
     *
     * @return string[]
     */
    public function getIgnorePaths()
    {
        return $this->primary->getIgnorePaths() ?: $this->secondary->getIgnorePaths();
    }

    /**
     * Get list of extensions to scan
     *
     * @return string[]
     */
    public function getExtensions()
    {
        return $this->primary->getExtensions() ?: $this->secondary->getExtensions();
    }

    /**
     * Get list of whitelisted rules
     *
     * @return string[]
     */
    public function getRuleWhitelist()
    {
        return $this->primary->getRuleWhitelist() ?: $this->secondary->getRuleWhitelist();
    }

    /**
     * Get list of blacklisted rules
     *
     * @return string[]
     */
    public function getRuleBlacklist()
    {
        return $this->primary->getRuleBlacklist() ?: $this->secondary->getRuleBlacklist();
    }

    /**
     * Check if annotations should be disabled
     *
     * @return boolean
     */
    public function disableAnnotations()
    {
        return $this->primary->disableAnnotations() || $this->secondary->disableAnnotations();
    }
}
