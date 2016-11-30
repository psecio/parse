<?php

namespace Psecio\Parse\Conf;

use Mockery as m;

class UserConfTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('format')
                ->andReturn('foobar')
                ->mock()
        );
        $this->assertSame('foobar', $conf->getFormat());
    }

    public function testPaths()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getArgument')
                ->with('path')
                ->andReturn(['path'])
                ->mock()
        );
        $this->assertSame(['path'], $conf->getPaths());
    }

    public function testIgnorePaths()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('ignore-paths')
                ->andReturn('path1,path2')
                ->mock()
        );
        $this->assertSame(['path1', 'path2'], $conf->getIgnorePaths());
    }

    public function testExtensions()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('extensions')
                ->andReturn('php,,phps')
                ->mock()
        );
        $this->assertSame(['php', 'phps'], array_values($conf->getExtensions()));
    }

    public function testRuleWhitelist()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('whitelist-rules')
                ->andReturn('')
                ->mock()
        );
        $this->assertSame([], $conf->getRuleWhitelist());
    }

    public function testRuleBlacklist()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('blacklist-rules')
                ->andReturn('rule')
                ->mock()
        );
        $this->assertSame(['rule'], $conf->getRuleBlacklist());
    }

    public function testDisableAnnotations()
    {
        $conf = new UserConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('disable-annotations')
                ->andReturn(true)
                ->mock()
        );
        $this->assertTrue($conf->disableAnnotations());
    }
}
