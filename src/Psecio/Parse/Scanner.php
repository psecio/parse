<?php

namespace Psecio\Parse;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Scanner
{
    /**
     * Target (directory/file) for evaluation
     * @var string
     */
    private $target;

    /**
     * php-parser instance
     * @var \PhpParser\Parser
     */
    private $parser;

    private $logPath = '/tmp/parse-track.log';

    /**
     * Init the object and set target (if given) and create parser
     * @param [type] $target [description]
     */
    public function __construct($target = null)
    {
        if ($target !== null) {
            $this->setTarget($target);
        }
        $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
    }

    /**
     * Set the target for evaluation
     *
     * @param string $target File/path for evaluation
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Get the current evaluation target
     *
     * @return string Target file/directory path
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get the current tests set and return a collection
     *
     * @param string $testPath Test path (file system location)
     * @param object $logger Logger instance [optional]
     * @param array $testList Test name list (inclusion)
     * @param array $excludeList Test name list (exclusion)
     * @return \Psecio\Parse\TestCollection instance
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

        $tests = new \Psecio\Parse\TestCollection($testSet, $logger);
        return $tests;
    }

    /**
     * Execute the scan
     *
     * @param boolean $debug Show debug information
     * @param array $testList List of tests to execute [optional]
     * @return array Set of files with any matches attached
     */
    public function execute($debug = false, array $testList = array(), array $excludeList = array())
    {
        ob_start();
        $target = $this->getTarget();

        $directory = new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = array();

        $logger = new Logger('scanner');
        $logger->pushHandler(new StreamHandler($this->logPath, Logger::INFO));

        $tests = $this->getTests(__DIR__.'/Tests', $logger, $testList, $excludeList);

        foreach ($iterator as $info) {
            echo '.'; ob_flush();

            $pathname = $info->getPathname();

            // Having .phps is a really bad thing....throw an exception if it's found
            if (strtolower(substr($pathname, -4)) == 'phps') {
                throw new \Exception('You have a .phps file - REMOVE NOW: '.$pathname);
            }
            if (strtolower(substr($pathname, -3)) !== 'php') {
                continue;
            }

            $logger->addInfo('Scanning file: '.$pathname);

            $file = new \Psecio\Parse\File($pathname);
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
        ob_end_flush();

        return $files;
    }
}