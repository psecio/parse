<?php

namespace Psecio\Parse\DocComment;

/**
 * Interface for a DocBlock comment parser
 */
interface DocCommentInterface
{
    /**
     * Parse comment
     *
     * @param string $comment
     */
    public function __construct($comment);

    /**
     * Get doc comment summary
     *
     * @return string
     */
    public function getSummary();

    /**
     * Get doc block body
     *
     * @return string
     */
    public function getBody();

    /**
     * Get defined tags
     *
     * @return array
     */
    public function getTags();

    /**
     * Get tag values matching $tagName
     *
     * @param string $tagName
     *
     * @return array  List of matching values
     */
    public function getMatchingTags($tagName);

    /**
     * Get tag values matching $tagName, case insensitively
     *
     * @param string $tagName
     *
     * @return array  List of matching values
     */
    public function getIMatchingTags($tagName);

    /**
     * Get the original, raw comment
     *
     * @return string
     */
    public function getRawComment();
}
