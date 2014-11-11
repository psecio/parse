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
     * Execute the scan
     *
     * @param boolean $debug Show debug information
     * @return array Set of files with any matches attached
     */
    public function execute($debug = false)
    {
        $target = $this->getTarget();

        $directory = new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = array();

        $logger = new Logger('scanner');
        $logger->pushHandler(new StreamHandler($this->logPath, Logger::INFO));

        $testIterator = new \DirectoryIterator(__DIR__.'/Tests');
        $testSet = array();
        foreach ($testIterator as $file) {
            if (!$file->isDot()) {
                $testSet[] = array(
                    'path' => $file->getPathName(),
                    'name' => str_replace('.php', '', $file->getFileName())
                );
            }
        }
        $tests = new \Psecio\Parse\TestCollection($testSet, $logger);

        foreach ($iterator as $info) {
            $pathname = $info->getPathname();
            $logger->addInfo('Scanning file: '.$pathname);

            // Having .phps is a really bad thing....throw an exception if it's found
            if (strtolower(substr($pathname, -4)) == 'phps') {
                throw new \Exception('You have a .phps file - REMOVE NOW: '.$pathname);
            }
            if (strtolower(substr($pathname, -3)) !== 'php') {
                continue;
            }

            $file = new \Psecio\Parse\File($pathname);
            $visitor = new \Psecio\Parse\NodeVisitor($tests, $file, $logger);
            $traverser = new \PhpParser\NodeTraverser;
            $traverser->addVisitor($visitor);

            // We need to recurse through the nodes and run our tests on each node
            try {
                $stmts = $this->parser->parse($file->getContents());
// print_r($stmts);
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