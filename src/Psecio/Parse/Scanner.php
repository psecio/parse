<?php

namespace Psecio\Parse;

class Scanner
{
    private $target;
    private $parser;

    public function __construct($target = null)
    {
        if ($target !== null) {
            $this->setTarget($target);
        }
        $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }
    public function getTarget()
    {
        return $this->target;
    }

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
                    // $this->recurse($stmts, $match, $file);

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