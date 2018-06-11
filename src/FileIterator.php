<?php

namespace Psecio\Parse;

use IteratorAggregate;
use Countable;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use ArrayIterator;
use SplFileInfo;
use RuntimeException;

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
     * @var string[] List of file extensions to include when scanning dirs
     */
    private $extensions = [];

    /**
     * Append paths to iterator
     *
     * @param  string[] $paths       List of paths to scan
     * @param  string[] $ignorePaths List of paths to ignore
     * @param  string[] $extensions  List of file extensions to include when scanning dirs
     * @throws RuntimeException      If the list of paths to scan is empty
     */
    public function __construct(array $paths, array $ignorePaths = array(), array $extensions = array('php'))
    {
        if (empty($paths)) {
            throw new RuntimeException('No paths to scan');
        }

        $this->addExtensions($extensions);

        foreach ($ignorePaths as $path) {
            $this->addIgnorePath($path);
        }

        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    /**
     * Add list of file extensions to include when scanning dirs
     *
     * @param  string[] $extensions
     * @return void
     */
    public function addExtensions(array $extensions)
    {
        $this->extensions = array_merge($this->extensions, $extensions);
    }

    /**
     * Add a path to the list of ignored paths
     *
     * Non existing paths are silently skipped.
     *
     * @param  string $path
     * @return void
     */
    public function addIgnorePath($path)
    {
        $realPath = realpath($path);
        if ($realPath === false) {
            return false;
        }
        $splFileInfo = new SplFileInfo($realPath);

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
        if (is_dir($path)) {
            $this->addDirectory($path);
        } else {
            $realPath = realpath($path);
            if ($realPath === false) {
                return false;
            }
            $this->addFile(new SplFileInfo($realPath));
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

    /**
     * Recursicely add files in directory
     *
     * @see FileIterator::isValidFile() Only files that are considered valid are added
     *
     * @param  string $directory Pathname of directory
     * @return void
     */
    private function addDirectory($directory)
    {
        $directory = realpath($directory);
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                FilesystemIterator::SKIP_DOTS
            )
        );
        foreach ($iterator as $splFileInfo) {
            if ($this->isValidFile($splFileInfo)) {
                $this->addFile($splFileInfo);
            }
        }
    }

    /**
     * Add a SplFileInfo object to iterator
     *
     * @param  SplFileInfo $splFileInfo
     * @return void
     */
    private function addFile(SplFileInfo $splFileInfo)
    {
        $this->files[$splFileInfo->getRealPath()] = new File($splFileInfo);
    }

    /**
     * Check of file should be included
     *
     * Returns false if the file extension is not valid or
     * if the file is in the ignore list.
     *
     * @param  SplFileInfo $splFileInfo
     * @return boolean
     */
    private function isValidFile(SplFileInfo $splFileInfo)
    {
        if (!in_array($splFileInfo->getExtension(), $this->extensions, true)) {
            return false;
        }

        $realPath = $splFileInfo->getRealPath();

        if (in_array($realPath, $this->ignorePaths['files'], true)) {
            return false;
        }

        foreach ($this->ignorePaths['dirs'] as $ignoreDir) {
            if (strpos($realPath, $ignoreDir) === 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert the interator to an array (return current files)
     *
     * @return array Set of current files
     */
    public function toArray()
    {
        return $this->files;
    }

    /**
     * Get the full paths for the current files
     *
     * @return array Set of file paths
     */
    public function getPaths()
    {
        $paths = [];
        foreach ($this->files as $file) {
            $paths[] = $file->getPath();
        }
        return $paths;
    }
}
