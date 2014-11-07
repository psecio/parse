<?php

namespace Psecio\Parse;

class File
{
    private $path;
    private $contents;
    private $status = true;
    private $errors = array();
    private $matches = array();

    public function __construct($path)
    {
        $this->setPath($path);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }
    public function getPath()
    {
        return $this->path;
    }

    public function getContents()
    {
        if ($this->contents === null) {
            $this->contents = file_get_contents($this->getPath());
        }
        return $this->contents;
    }
    public function setContents($contents)
    {
        $this->contents = $contents;
    }

    public function addMatch($match, $path)
    {
        $this->matches[] = array(
            'path' => $path,
            'node' => $match
        );
    }
    public function getMatches()
    {
        return $this->matches;
    }
}