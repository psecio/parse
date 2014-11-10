<?php

namespace Psecio\Parse;

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
     * @param array $matches Set of "paths" to evaluate and match
     * @return array Set of files with any matches attached
     */
    public function execute(array $matches)
    {
        $target = $this->getTarget();

        $directory = new \RecursiveDirectoryIterator($target, \FilesystemIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = array();

        foreach ($iterator as $info) {
            $pathname = $info->getPathname();
            if (strtolower(substr($pathname, -3)) !== 'php') {
                continue;
            }

            $file = new \Psecio\Parse\File($pathname);

            foreach ($matches as $matchPath) {
                $match = new \Psecio\Parse\MatchPath();
                $match->setPath($matchPath);

                try {
                    $stmts = $this->parser->parse($file->getContents());
                    foreach ($stmts as $node) {
                        $match->evaluate($node, $match->getPath(), $file);
                    }

                } catch (\PhpParser\Error $e) {
                    echo 'Parse Error: '.$e->getMessage();
                };
            }
            $files[] = $file;
        }

        return $files;
    }
}