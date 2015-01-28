<?php

namespace Psecio\Parse\Conf;

/**
 * Scan configuration interface
 */
interface Configuration
{
    /**
     * Get output format identifier
     *
     * @return string
     */
    public function getFormat();

    /**
     * Get list of paths to scan
     *
     * @return string[]
     */
    public function getPaths();

    /**
     * Get list of paths to ignore
     *
     * @return string[]
     */
    public function getIgnorePaths();

    /**
     * Get list of extensions to scan
     *
     * @return string[]
     */
    public function getExtensions();

    /**
     * Get list of whitelisted rules
     *
     * @return string[]
     */
    public function getRuleWhitelist();

    /**
     * Get list of blacklisted rules
     *
     * @return string[]
     */
    public function getRuleBlacklist();

    /**
     * Check if annotations should be disabled
     *
     * @return boolean
     */
    public function disableAnnotations();
}
