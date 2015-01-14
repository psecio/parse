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
     * @var array Paths that sould be ignored
     */
    private $ignorePaths = ['dirs'=> [], 'files' => []];

    /**
     * Append paths to iterator
     *
     * @param string[] $paths       List of paths to include
     * @param string[] $ignorePaths List of paths to ignore
     */
    public function __construct(array $paths = array(), array $ignorePaths = array())
    {
        foreach ($ignorePaths as $path) {
            $this->addIgnorePath($path);
        }
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * Add a path to the list of ignored paths
     *
     * Non existing paths are silently skipped
     *
     * @param  string $path
     * @return void
     */
    public function addIgnorePath($path)
    {
        $splFileInfo = new SplFileInfo($path);
        if ($splFileInfo->isFile()) {
            $this->ignorePaths['files'][] = $splFileInfo->getRealPath();
        } elseif ($splFileInfo->isDir()) {
            $this->ignorePaths['dirs'][] = $splFileInfo->getRealPath() . DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Add a path to iterator
     *
     * @param  string $path
     * @return void
     */
    public function addPath($path)
    {
        is_dir($path)
            ? $this->addDirectory($path)
            : $this->addSplFileInfo(new SplFileInfo($path));
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

    /**
     * Recursicely add files in directory
     *
     * @param  string $directory Pathname of directory
     * @return void
     */
    private function addDirectory($directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                FilesystemIterator::SKIP_DOTS
            )
        );
        foreach ($iterator as $splFileInfo) {
            $this->addSplFileInfo($splFileInfo);
        }
    }

    /**
     * Add SplFileInfo object to iterator
     *
     * @param  SplFileInfo $splFileInfo
     * @return void
     */
    private function addSplFileInfo(SplFileInfo $splFileInfo)
    {
        $realPath = $splFileInfo->getRealPath();

        if (in_array($realPath, $this->ignorePaths['files'])) {
            return;
        }

        foreach ($this->ignorePaths['dirs'] as $ignoreDir) {
            if (strpos($realPath, $ignoreDir) === 0) {
                return;
            }
        }

        $this->files[$realPath] = new File($splFileInfo);
    }
}
