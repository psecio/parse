<?php

namespace Psecio\Parse;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpParser\Parser;
use PhpParser\Lexer\Emulative as Lexer;

/**
 * Core engine: iterates over source files and applies registered tests
 */
class Scanner
{
    /**
     * @var Parser PhpParser instance
     */
    private $parser;

    /**
     * Optionally inject parser
     *
     * @param Parser|null $parser
     */
    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: new Parser(new Lexer);
    }

    /**
     * Get the current tests set and return a collection
     *
     * @param  string $testPath Test path (file system location)
     * @param  object $logger Logger instance [optional]
     * @param  array $testList Test name list (inclusion)
     * @param  array $excludeList Test name list (exclusion)
     * @return TestCollection instance
     */
    public function getTests($testPath, $logger = null, array $testList = array(), array $excludeList = array())
    {
        $testIterator = new \DirectoryIterator(__DIR__.'/Tests');
        $testSet = array();

        foreach ($testIterator as $file) {
            if (!$file->isDot()) {
                $basename = $file->getBasename('.php');

                // Skip based on the include and exclude lists
                if (!empty($testList) && !in_array($basename, $testList)) {
                    continue;
                }
                if (!empty($excludeList) && in_array($basename, $excludeList)) {
                    continue;
                }

                $testSet[] = array(
                    'path' => $file->getPathName(),
                    'name' => str_replace('.php', '', $file->getFileName())
                );
            }
        }

        $tests = new TestCollection($testSet, $logger);
        return $tests;
    }

    /**
     * Execute the scan
     *
     * @param  string[] $paths Paths to scan
     * @param  string[] $testList List of tests to execute [optional]
     * @return File[]   Set of files with any matches attached
     */
    public function execute(array $paths, array $testList = array(), array $excludeList = array())
    {
        $logger = new Logger('scanner');
        $logger->pushHandler(new StreamHandler('/tmp/parse-track.log', Logger::INFO));

        $tests = $this->getTests(__DIR__.'/Tests', $logger, $testList, $excludeList);

        $files = [];

        foreach ($paths as $path) {
            $files = array_merge(
                $files,
                $this->scanPath($path, $logger, $tests)
            );
        }

        return $files;
    }

    /**
     * Scan a specific path
     *
     * @param  string         $path Path to scan
     * @param  Logger         $logger
     * @param  TestCollection $tests
     * @return File[]         Set of files with any matches attached
     */
    private function scanPath($path, Logger $logger, TestCollection $tests)
    {
        $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = array();

        foreach ($iterator as $info) {
            echo '.';

            $pathname = $info->getPathname();

            // Having .phps is a really bad thing....throw an exception if it's found
            if (strtolower(substr($pathname, -4)) == 'phps') {
                throw new \Exception('You have a .phps file - REMOVE NOW: '.$pathname);
            }
            if (strtolower(substr($pathname, -3)) !== 'php') {
                continue;
            }

            $logger->addInfo('Scanning file: '.$pathname);

            $file = new File($pathname);
            $visitor = new \Psecio\Parse\NodeVisitor($tests, $file, $logger);
            $traverser = new \PhpParser\NodeTraverser;
            $traverser->addVisitor($visitor);

            // We need to recurse through the nodes and run our tests on each node
            try {
                $stmts = $this->parser->parse($file->getContents());
                $stmts = $traverser->traverse($stmts);

                $results = $visitor->getResults();
                $file->setMatches($results);

                if (count($results) > 0) {
                    $logger->addInfo(
                        'Matches found',
                        array('path' => $pathname, 'count' => count($results))
                    );
                }

            } catch (\PhpParser\Error $e) {
                echo 'Parse Error: '.$e->getMessage();
            };

            $files[] = $file;
        }

        return $files;
    }
}
