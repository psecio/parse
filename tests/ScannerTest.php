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
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onNodeFailure')->mock()
        );

        $scanner->onNodeFailure(
            m::mock('\Psecio\Parse\RuleInterface'),
            m::mock('\PhpParser\Node'),
            m::mock('\Psecio\Parse\File')
        );
    }

    public function testErrorOnPhpsFile()
    {
        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('isPathMatch')->once()->with('/\.phps$/i')->andReturn(true);
        $file->shouldReceive('getContents')->once()->andReturn('');

        $dispatcher = $this->createErrorDispatcherMock();

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onNodeFailure', 'setFile')->mock(),
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
        $file->shouldReceive('isPathMatch')->once()->with('/\.phps$/i')->andReturn(true);
        $file->shouldReceive('getContents')->once()->andReturn('');

        $dispatcher = $this->createErrorDispatcherMock();

        $scanner = new Scanner(
            $dispatcher,
            m::mock('\Psecio\Parse\CallbackVisitor')->shouldReceive('onNodeFailure', 'setFile')->mock(),
            m::mock('\PhpParser\Parser')->shouldReceive('parse')->andThrow(new \PhpParser\Error(''))->mock(),
            m::mock('\PhpParser\NodeTraverser')
                ->shouldReceive('addVisitor')->shouldReceive('traverse')->mock()
        );

        $scanner->scan(
            m::mock('\Psecio\Parse\FileIterator')
                ->shouldReceive('getIterator')
                ->andReturn(new \ArrayIterator([$file]))
                ->mock()
        );
    }

    private function createErrorDispatcherMock()
    {
        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_START);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_OPEN,
            m::type('\Psecio\Parse\Event\FileEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(
            Scanner::FILE_ERROR,
            m::type('\Psecio\Parse\Event\ErrorEvent')
        );
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::FILE_CLOSE);
        $dispatcher->shouldReceive('dispatch')->ordered()->once()->with(Scanner::SCAN_COMPLETE);

        return $dispatcher;
    }
}
