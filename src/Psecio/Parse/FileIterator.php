<?php

namespace Psecio\Parse;

use IteratorAggregate;
use Countable;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use ArrayIterator;

/**
 * Responsible for locating and iterating over File objects
 */
class FileIterator implements IteratorAggregate, Countable
{
    /**
     * @var File[] Array of File objects using paths as keys
     */
    private $files;

    /**
     * Append paths to iterator
     *
     * @param string[] $paths
     */
    public function __construct(array $paths = array())
    {
        foreach ($paths as $path) {
            $this->appendDir($path);
        }
    }

    /**
     * Recursicely append files in directory
     *
     * @param  string $directory Pathname of directory
     * @return void
     */
    public function appendDir($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                FilesystemIterator::SKIP_DOTS
            )
        );
        foreach ($iterator as $splFileInfo) {
            $this->files[$splFileInfo->getPathname()] = new File($splFileInfo);
        }
    }

    /**
     * Get iterator whith file paths as keys and File objects as values
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->files);
    }

    /**
     * Return a count of files in iterator
     *
     * @return integer
     */
    public function count()
    {
        return count($this->files);
    }
}
