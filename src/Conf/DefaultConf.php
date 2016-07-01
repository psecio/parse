<?php

namespace Psecio\Parse\Conf;

/**
 * Default configurations
 */
class DefaultConf implements Configuration
{
    public function getFormat()
    {
        return 'progress';
    }

    public function getPaths()
    {
        return [];
    }

    public function getIgnorePaths()
    {
        return [];
    }

    public function getExtensions()
    {
        return ['php', 'phps', 'phtml', 'php5'];
    }

    public function getRuleWhitelist()
    {
        return [];
    }

    public function getRuleBlacklist()
    {
        return [];
    }

    public function disableAnnotations()
    {
        return false;
    }
}
