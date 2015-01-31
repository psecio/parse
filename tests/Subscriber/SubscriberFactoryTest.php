<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

/**
 * @covers \Psecio\Parse\Subscriber\SubscriberFactory
 */
class SubscriberFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnUnknownFormat()
    {
        $this->setExpectedException('RuntimeException');
        new SubscriberFactory(
            'invalid-format-identifier',
            m::mock('Symfony\Component\Console\Output\OutputInterface')
        );
    }

    public function testDebugTransition()
    {
        $factory = new SubscriberFactory(
            SubscriberFactory::FORMAT_DOTS,
            m::mock('Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('isVeryVerbose')
                ->andReturn(true)
                ->mock()
        );
        $this->assertSame(
            SubscriberFactory::FORMAT_DEBUG,
            $factory->getFormat()
        );
    }

    public function testVerboseTransition()
    {
        $factory = new SubscriberFactory(
            SubscriberFactory::FORMAT_DOTS,
            m::mock('Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('isVeryVerbose')->andReturn(false)
                ->shouldReceive('isVerbose')->andReturn(true)
                ->mock()
        );
        $this->assertSame(
            SubscriberFactory::FORMAT_LINES,
            $factory->getFormat()
        );
    }

    public function testNoAnsiTransition()
    {
        $factory = new SubscriberFactory(
            SubscriberFactory::FORMAT_PROGRESS,
            m::mock('Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('isVeryVerbose')->andReturn(false)
                ->shouldReceive('isVerbose')->andReturn(false)
                ->shouldReceive('isDecorated')->andReturn(false)
                ->mock()
        );
        $this->assertSame(
            SubscriberFactory::FORMAT_DOTS,
            $factory->getFormat()
        );
    }

    public function testAddSubscribersToDispatcher()
    {
        $factory = new SubscriberFactory(
            SubscriberFactory::FORMAT_XML,
            m::mock('Symfony\Component\Console\Output\OutputInterface')
                ->shouldReceive('isVeryVerbose')->andReturn(false)
                ->shouldReceive('isVerbose')->andReturn(false)
                ->shouldReceive('isDecorated')->andReturn(true)
                ->mock()
        );

        $factory->addSubscribersTo(
            m::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface')
                ->shouldReceive('addSubscriber')
                ->once()
                ->mock()
        );
    }
}
