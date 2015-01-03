<?php

namespace Psecio\Parse;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpParser\Parser;
use PhpParser\Lexer\Emulative as Lexer;
use PhpParser\NodeTraverser;
use PhpParser\Node;

/**
 * Core engine, iterates over source files and evaluates tests
 */
class Scanner implements Event\Events
{
    /**
     * @var EventDispatcherInterface Registered event dispatcher
     */
    private $dispatcher;

    /**
     * @var Parser PhpParser instance
     */
    private $parser;

    /**
     * @var NodeTraverser Traverser
     */
    private $traverser;

    /**
     * @var CallbackVisitor Node visitor
     */
    private $visitor;

    /**
     * Optionally inject parser
     *
     * @param EventDispatcherInterface $dispatcher
     * @param CallbackVisitor $visitor
     * @param Parser|null $parser
     * @param NodeTraverser|null $traverser
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        CallbackVisitor $visitor,
        Parser $parser = null,
        NodeTraverser $traverser = null
    ) {
        $this->dispatcher = $dispatcher;
        $this->visitor = $visitor;
        $this->parser = $parser ?: new Parser(new Lexer);
        $this->traverser = $traverser ?: new NodeTraverser;
        $this->visitor->onTestFail([$this, 'onTestFail']);
        $this->traverser->addVisitor($this->visitor);
    }

    /**
     * Test fail callback
     *
     * @param  TestInterface $test
     * @param  Node $node
     * @param  File $file
     * @return void
     */
    public function onTestFail(TestInterface $test, Node $node, File $file)
    {
        $this->dispatcher->dispatch(self::FILE_ISSUE, new Event\IssueEvent($test, $node, $file));
    }

    /**
     * Execute the scan
     *
     * @param  FileIterator $fileIterator Iterator with files to scan
     * @return void
     */
    public function scan(FileIterator $fileIterator)
    {
        $this->dispatcher->dispatch(self::SCAN_START);

        foreach ($fileIterator as $file) {
            if ($file->isPathMatch('/\.phps$/i')) {
                $this->dispatcher->dispatch(
                    self::FILE_ERROR,
                    new Event\MessageEvent('You have a .phps file - REMOVE NOW', $file)
                );
            } elseif (!$file->isPathMatch('/\.php$/i')) {
                $this->dispatcher->dispatch(
                    self::DEBUG,
                    new Event\MessageEvent("Skipping " . $file->getPath(), $file)
                );
                continue;
            }

            $this->dispatcher->dispatch(self::FILE_OPEN, new Event\FileEvent($file));

            try {
                $this->visitor->setFile($file);
                $this->traverser->traverse($this->parser->parse($file->getContents()));
            } catch (\PhpParser\Error $e) {
                $this->dispatcher->dispatch(
                    self::FILE_ERROR,
                    new Event\MessageEvent($e->getMessage(), $file)
                );
            }

            $this->dispatcher->dispatch(self::FILE_CLOSE);
        }

        $this->dispatcher->dispatch(self::SCAN_COMPLETE);
    }
}
