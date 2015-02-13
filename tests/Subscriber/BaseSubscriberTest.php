<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class BaseSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscription()
    {
        $this->assertInternalType(
            'array',
            BaseSubscriber::getSubscribedEvents()
        );
    }

    public function testEmptyMethods()
    {
        $subscriber = m::mock('\Psecio\Parse\Subscriber\BaseSubscriber[]');
        $this->assertNull($subscriber->onScanStart(m::mock('\Psecio\Parse\Event\MessageEvent')));
        $this->assertNull($subscriber->onScanComplete());
        $this->assertNull($subscriber->onFileOpen(m::mock('\Psecio\Parse\Event\FileEvent')));
        $this->assertNull($subscriber->onFileClose());
        $this->assertNull($subscriber->onFileIssue(m::mock('\Psecio\Parse\Event\IssueEvent')));
        $this->assertNull($subscriber->onFileError(m::mock('\Psecio\Parse\Event\ErrorEvent')));
        $this->assertNull($subscriber->onDebug(m::mock('\Psecio\Parse\Event\MessageEvent')));
    }
}
