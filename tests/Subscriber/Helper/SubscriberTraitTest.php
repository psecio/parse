<?php

namespace Psecio\Parse\Subscriber\Helper;

use Mockery as m;

class SubscriberTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscription()
    {
        $subscriber = $this->createSubscriberMock();
        $this->assertInternalType(
            'array',
            $subscriber::getSubscribedEvents()
        );
    }

    public function testEmptyMethods()
    {
        $subscriber = $this->createSubscriberMock();
        $this->assertNull($subscriber->onScanStart());
        $this->assertNull($subscriber->onScanComplete());
        $this->assertNull($subscriber->onFileOpen(m::mock('\Psecio\Parse\Event\FileEvent')));
        $this->assertNull($subscriber->onFileClose());
        $this->assertNull($subscriber->onFileIssue(m::mock('\Psecio\Parse\Event\IssueEvent')));
        $this->assertNull($subscriber->onFileError(m::mock('\Psecio\Parse\Event\MessageEvent')));
        $this->assertNull($subscriber->onDebug(m::mock('\Psecio\Parse\Event\MessageEvent')));
    }

    private function createSubscriberMock()
    {
        return $this->getObjectForTrait('Psecio\Parse\Subscriber\Helper\SubscriberTrait');
    }
}
