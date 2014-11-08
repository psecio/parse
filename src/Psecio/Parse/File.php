<?php

namespace Psecio\Parse;

class File
{
    /**
     * Path to file for object
     * @var string
     */
    private $path;

    /**
     * File contents
     * @var string
     */
    private $contents;

    /**
     * Matches found for path evaluation
     * @var array
     */
    private $matches = array();

    /**
     * Init the object and set the path
     *
     * @param string $path Path to file
     */
    public function __construct($path)
    {
        $this->setPath($path);
    }

    /**
     * Set the current instance file path
     *
     * @param string $path File path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the current file path
     *
     * @return string Current file path
     */
    public function getPath()
    {
        return $this->path;
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
     * Add a node instance to match on file
     *
     * @param object $match Node instance
     * @param string $path Path (DSL) matched against
     */
    public function addMatch($match, $path)
    {
        $this->matches[] = array(
            'path' => $path,
            'node' => $match
        );
    }

    /**
     * Get the full list of matches on File instance
     *
     * @return array Matches set
     */
    public function getMatches()
    {
        return $this->matches;
    }
}