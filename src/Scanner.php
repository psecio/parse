<?php

namespace Psecio\Parse;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpParser\Parser;
use PhpParser\Lexer\Emulative as Lexer;
use PhpParser\NodeTraverser;
use PhpParser\Node;

use PhpParser\Error;
use PhpParser\ParserFactory;

/**
 * Iterates over and validates files, dispatching events
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
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $this->traverser = $traverser ?: new NodeTraverser;
        $this->visitor->onNodeFailure([$this, 'onNodeFailure']);
        $this->traverser->addVisitor($this->visitor);
    }

    /**
     * Node fail callback
     *
     * @param  RuleInterface $rule
     * @param  Node $node
     * @param  File $file
     * @return void
     */
    public function onNodeFailure(RuleInterface $rule, Node $node, File $file)
    {
        $this->dispatcher->dispatch(self::FILE_ISSUE, new Event\IssueEvent($rule, $node, $file));
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
            $this->dispatcher->dispatch(self::FILE_OPEN, new Event\FileEvent($file));

            if ($file->isPathMatch('/\.phps$/i')) {
                $this->dispatcher->dispatch(
                    self::FILE_ERROR,
                    new Event\ErrorEvent('You have a .phps file - REMOVE NOW', $file)
                );
            }

            try {
                $this->visitor->setFile($file);
                $this->traverser->traverse($this->parser->parse($file->getContents()));
            } catch (\PhpParser\Error $e) {
                $this->dispatcher->dispatch(
                    self::FILE_ERROR,
                    new Event\ErrorEvent($e->getMessage(), $file)
                );
            }

            $this->dispatcher->dispatch(self::FILE_CLOSE);
        }

        $this->dispatcher->dispatch(self::SCAN_COMPLETE);
    }
}
