<?php

namespace Psecio\Parse;

use Mockery as m;

class ScannerTest extends \PHPUnit_Framework_TestCase
{
    public function testCallbackOnIssue()
    {
        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->shouldReceive('dispatch')
            ->once()
            ->with(Scanner::FILE_ISSUE, m::type('\Psecio\Parse\Event\IssueEvent'))
            ->mock();

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onTestFail')->mock()
        );

        $scanner->onTestFail(
            m::mock('\Psecio\Parse\TestInterface'),
            m::mock('\PhpParser\Node'),
            m::mock('\Psecio\Parse\File')
        );
    }

    public function testSkipNonPhpFile()
    {
        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('isPathMatch')->once()->with('/\.phps$/i')->andReturn(false);
        $file->shouldReceive('isPathMatch')->once()->with('/\.php$/i')->andReturn(false);
        $file->shouldReceive('getPath')->once()->andReturn('');

        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_START);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::DEBUG,
            m::type('\Psecio\Parse\Event\MessageEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_COMPLETE);

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onTestFail')->mock(),
            m::mock('\PhpParser\Parser'),
            m::mock('\PhpParser\NodeTraverser')->shouldReceive('addVisitor')->mock()
        );

        $scanner->scan(
            m::mock('\Psecio\Parse\FileIterator')
                ->shouldReceive('getIterator')
                ->andReturn(new \ArrayIterator([$file]))
                ->mock()
        );
    }

    public function testErrorOnPhpsFile()
    {
        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('isPathMatch')->once()->with('/\.phps$/i')->andReturn(true);
        $file->shouldReceive('getContents')->once()->andReturn('');

        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_START);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_ERROR,
            m::type('\Psecio\Parse\Event\MessageEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_OPEN,
            m::type('\Psecio\Parse\Event\FileEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::FILE_CLOSE);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_COMPLETE);

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onTestFail', 'setFile')->mock(),
            m::mock('\PhpParser\Parser')->shouldReceive('parse')->andReturn([])->mock(),
            m::mock('\PhpParser\NodeTraverser')->shouldReceive('traverse', 'addVisitor')->mock()
        );

        $scanner->scan(
            m::mock('\Psecio\Parse\FileIterator')
                ->shouldReceive('getIterator')
                ->andReturn(new \ArrayIterator([$file]))
                ->mock()
        );
    }

    public function testErrorOnParseException()
    {
        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('isPathMatch')->once()->with('/\.phps$/i')->andReturn(false);
        $file->shouldReceive('isPathMatch')->once()->with('/\.php$/i')->andReturn(true);
        $file->shouldReceive('getContents')->once()->andReturn('');

        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_START);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_OPEN,
            m::type('\Psecio\Parse\Event\FileEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_ERROR,
            m::type('\Psecio\Parse\Event\MessageEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::FILE_CLOSE);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_COMPLETE);

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onTestFail', 'setFile')->mock(),
            m::mock('\PhpParser\Parser')->shouldReceive('parse')->andThrow(new \PhpParser\Error(''))->mock(),
            m::mock('\PhpParser\NodeTraverser')->shouldReceive('addVisitor')->mock()
        );

        $scanner->scan(
            m::mock('\Psecio\Parse\FileIterator')
                ->shouldReceive('getIterator')
                ->andReturn(new \ArrayIterator([$file]))
                ->mock()
        );
    }
}
