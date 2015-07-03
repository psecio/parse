<?php

namespace Psecio\Parse\DocComment;

/**
 * Line based comment parser
 */
class DocComment implements DocCommentInterface
{
    /**
     * Parsing starts now
     */
    const STATE_INIT = 0;

    /**
     * Next line belongs to summary
     */
    const STATE_SUMMARY = 1;

    /**
     * Next line belongs to body
     */
    const STATE_BODY = 2;

    /**
     * Remaining content parsed as tags
     */
    const STATE_TAG = 3;

    /**
     * Next line belongs to current tag
     */
    const STATE_IN_TAG = 4;

    /**
     * Next line should be ignored
     */
    const STATE_IGNORE = 5;

    /**
     * @var integer[] State transitions performed when an empty line is found
     */
    private $emptyLineStateTransitions = [
        self::STATE_INIT => self::STATE_SUMMARY,
        self::STATE_SUMMARY => self::STATE_BODY,
        self::STATE_BODY => self::STATE_BODY,
        self::STATE_TAG => self::STATE_TAG,
        self::STATE_IN_TAG => self::STATE_IGNORE,
        self::STATE_IGNORE => self::STATE_IGNORE
    ];

    /**
     * @var integer Current parsing state
     */
    private $state = self::STATE_INIT;

    /**
     * @var string Name of tag being parsed
     */
    private $currentTag;

    /**
     * @var string Content of tag being parsed
     */
    private $currentTagContent;

    /**
     * @var string Comment summary
     */
    private $summary = '';

    /**
     * @var string Comment body
     */
    private $body = '';

    /**
     * @var string  The original, raw comment
     */
    private $rawComment = '';

    /**
     * @var array Tags in comment
     */
    private $tags = [];

    /**
     * Parse comment
     *
     * @param string $comment
     */
    public function __construct($comment)
    {
        $this->rawComment = $comment;
        foreach (preg_split("/\r\n|\n|\r/", $comment) as $line) {
            $line = ltrim($line, "\t\0\x0B /*#");
            $line = rtrim($line, "\t\0\x0B /*");
            $this->parseLine($line);
        }
        $this->saveCurrentTag();
    }

    /**
     * Get the original, raw comment
     *
     * @return string
     */
    public function getRawComment()
    {
        return $this->rawComment;
    }

    /**
     * Get doc comment summary
     *
     * @return string
     */
    public function getSummary()
    {
        return trim($this->summary);
    }

    /**
     * Get doc block body
     *
     * @return string
     */
    public function getBody()
    {
        return trim($this->body);
    }

    /**
     * Get defined tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
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
        if (isset($this->tags[$tagName])) {
            return $this->tags[$tagName];
        }

        return array();
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
        $tagName = strtolower($tagName);

        $result = array();

        foreach ($this->tags as $name => $values) {
            if (strtolower($name) == $tagName) {
                $result = array_merge($result, $values);
            }
        }

        return $result;
    }

    /**
     * Sateful line parser
     *
     * @param  string $line
     * @return void
     */
    private function parseLine($line)
    {
        if (empty($line)) {
            $this->state = $this->emptyLineStateTransitions[$this->state];
        }

        if (strpos($line, '@') === 0) {
            $this->state = self::STATE_TAG;
        }

        switch ($this->state) {
            case self::STATE_INIT:
            case self::STATE_SUMMARY:
                $this->summary .= ' ' . $line;
                break;
            case self::STATE_BODY:
                $this->body .= "\n" . $line;
                break;
            case self::STATE_TAG:
                $this->saveCurrentTag();
                if (preg_match('/^@([\w\\\\]+)\s*(.*?)\s*$/', $line, $matches)) {
                    list(, $this->currentTag, $this->currentTagContent) = $matches;
                }
                $this->state = self::STATE_IN_TAG;
                break;
            case self::STATE_IN_TAG:
                $this->currentTagContent .= ' ' . $line;
                break;
        }
    }

    /**
     * Save the tag currently being parsed
     *
     * @return void
     */
    private function saveCurrentTag()
    {
        if (isset($this->currentTag)) {
            if (!isset($this->tags[$this->currentTag])) {
                $this->tags[$this->currentTag] = [];
            }
            $this->tags[$this->currentTag][] = trim($this->currentTagContent);
            unset($this->currentTag);
        }
    }
}
