<?php

namespace Psecio\Parse\Fakes;

use Psecio\Parse\DocComment\DocCommentInterface;

class FakeDocComment implements DocCommentInterface
{
    private $disabledRules = [];
    private $enabledRules = [];

    /**
     * Parse comment
     *
     * @param string $comment
     */
    public function __construct($comment = '', $disabled = [], $enabled = [])
    {
        $this->disabledRules = $disabled;
        $this->enabledRules = $enabled;
    }

    /**
     * Get doc comment summary
     *
     * @return string
     */
    public function getSummary()
    {
        return '';
    }

    /**
     * Get doc block body
     *
     * @return string
     */
    public function getBody()
    {
        return '';
    }

    /**
     * Get defined tags
     *
     * @return array
     */
    public function getTags()
    {
        return [];
    }

    /**
     * Get tag values matching $tagName
     *
     * @param string $tagName
     *
     * @return array  List of matching values
     */
    public function getMatchingTags($tagName)
    {
        return [];
    }

    /**
     * Get tag values matching $tagName, case insensitively
     *
     * @param string $tagName
     *
     * @return array  List of matching values
     */
    public function getIMatchingTags($tagName)
    {
        if (strtolower($tagName) == 'psecio\parse\disable') {
            return $this->disabledRules;
        }

        if (strtolower($tagName) == 'psecio\parse\enable') {
            return $this->enabledRules;
        }

        return [];
    }

    /**
     * Get the original, raw comment
     *
     * @return string
     */
    public function getRawComment()
    {
        return '';
    }
}
