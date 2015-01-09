<?php

namespace Psecio\Parse;

use SplFileInfo;

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
     * @var string File contents
     */
    private $contents;

    /**
     * Set the current instance file info
     *
     * @param SplFileInfo $splFileInfo
     */
    public function __construct(SplFileInfo $splFileInfo)
    {
        $this->splFileInfo = $splFileInfo;
    }

    /**
     * Get file info
     *
     * @return SplFileInfo
     */
    public function getSplFileInfo()
    {
        return $this->splFileInfo;
    }

    /**
     * Get the current file path
     *
     * @return string Current file path
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
        if ($this->contents === null) {
            $this->contents = file_get_contents($this->getPath());
        }
        return $this->contents;
    }

    /**
     * Set the current instance "contents" value
     *
     * @param string $contents File contents
     */
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    /**
     * Pull out the given lines from the current file contents
     *
     * @param  integer $startLine Start line
     * @param  integer $endLine End line [optional]
     * @return array Set of matching lines
     */
    public function getLines($startLine, $endLine = null)
    {
        if ($endLine === null) {
            $endLine = $startLine + 1;
        }

        return array_slice(
            explode("\n", $this->getContents()),
            $startLine-1,
            $endLine - $startLine
        );
    }
}
