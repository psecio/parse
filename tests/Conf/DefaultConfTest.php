<?php

namespace Psecio\Parse\Conf;

class DefaultConfTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $conf = new DefaultConf;
        $this->assertSame('progress', $conf->getFormat());
        $this->assertSame([], $conf->getPaths());
        $this->assertSame([], $conf->getIgnorePaths());
        $this->assertSame(['php', 'phps', 'phtml', 'php5'], $conf->getExtensions());
        $this->assertSame([], $conf->getRuleWhitelist());
        $this->assertSame([], $conf->getRuleBlacklist());
        $this->assertFalse($conf->disableAnnotations());
    }
}
