<?php

namespace Psecio\Parse;

use SplFileInfo;
use RuntimeException;
use PhpParser\Node;

/**
 * Wrapper around an SplFileInfo object
 */
class File
{
    /**
     * @var SplFileInfo Info about this file
     */
    private $splFileInfo;

    /**
     * @var string[] File contents
     */
    private $lines;

    /**
     * Set the SplFileInfo object
     *
     * @param  SplFileInfo $splFileInfo
     * @throws RuntimeException If file does not exist
     */
    public function __construct(SplFileInfo $splFileInfo)
    {
        if (!$splFileInfo->isReadable()) {
            throw new RuntimeException("Failed to open file '{$splFileInfo->getRealPath()}'");
        }
        $this->splFileInfo = $splFileInfo;
        $this->lines = file($splFileInfo->getRealPath(), FILE_IGNORE_NEW_LINES);
    }

    /**
     * Get to SplFileInfo object
     *
     * @return SplFileInfo
     */
    public function getSplFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * Get the file path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getSplFileInfo()->getPathname();
    }

    /**
     * Test if path matches a regular expression
     *
     * @param  string $regexp
     * @return bool
     */
    public function isPathMatch($regexp)
    {
        return !!preg_match($regexp, $this->getPath());
    }

    /**
     * Get the file contents
     *
     * @return string File contents
     */
    public function getContents()
    {
        return implode("\n", $this->lines);
    }

    /**
     * Pull out given lines from file contents
     *
     * @param  integer $startLine
     * @param  integer $endLine
     * @return string[]
     */
    public function fetchLines($startLine, $endLine = 0)
    {
        $startLine--;
        $endLine = $endLine ?: $startLine;
        $length = $endLine - $startLine;
        $length = $length ?: 1;

        return array_slice($this->lines, $startLine, $length);
    }

    /**
     * Fetch Node line contents
     *
     * @param  Node $node
     * @return string[]
     */
    public function fetchNode(Node $node)
    {
        $attr = $node->getAttributes();
        return $this->fetchLines($attr['startLine'], $attr['endLine']);
    }
}
