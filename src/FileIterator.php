<?php

namespace Psecio\Parse;

use IteratorAggregate;
use Countable;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use ArrayIterator;
use SplFileInfo;

/**
 * Responsible for iterating over filesystem paths
 */
class FileIterator implements IteratorAggregate, Countable
{
    /**
     * @var File[] Array of File objects using paths as keys
     */
    private $files = [];

    /**
     * Append paths to iterator
     *
     * @param string[] $paths
     */
    public function __construct(array $paths = array())
    {
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $this->appendDir($path);
                continue;
            }
            $this->appendFile($path);
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
            $this->files[$splFileInfo->getRealPath()] = new File($splFileInfo);
        }
    }

    /**
     * Append file to iterator
     *
     * @param  string $filename
     * @return void
     */
    public function appendFile($filename)
    {
        $splFileInfo = new SplFileInfo($filename);
        $this->files[$splFileInfo->getRealPath()] = new File($splFileInfo);
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
