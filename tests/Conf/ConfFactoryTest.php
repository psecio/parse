<?php

namespace Psecio\Parse\Conf;

use Mockery as m;

/**
 * @covers \Psecio\Parse\Conf\ConfFactory
 */
class ConfFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionOnUnknownConfFile()
    {
        $this->setExpectedException('RuntimeException');
        (new ConfFactory)->createConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')
                ->with('configuration')
                ->andReturn('this-file-does-not-exist')
                ->mock()
        );
    }

    public function testCustomConfFile()
    {
        $filename = sys_get_temp_dir() . '/' . uniqid('psecio-parse') . '.json';
        file_put_contents($filename, '{"extensions": ["foobar"]}');

        $conf = (new ConfFactory)->createConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')->with('configuration')->andReturn($filename)
                ->shouldReceive('getOption')->with('extensions')->andReturn('')
                ->mock()
        );
        $this->assertSame(['foobar'], $conf->getExtensions());

        unlink($filename);
    }

    public function testDefaultConfFile()
    {
        $cwd = getcwd();
        chdir(sys_get_temp_dir());

        $filename = sys_get_temp_dir() . '/psecio-parse.json';
        file_put_contents($filename, '{"extensions": ["foobar"]}');

        $confUsingDefaultFile = (new ConfFactory)->createConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')->with('configuration')->andReturn('')
                ->shouldReceive('getOption')->with('no-configuration')->andReturn(false)
                ->shouldReceive('getOption')->with('extensions')->andReturn('')
                ->mock()
        );
        $this->assertSame(['foobar'], $confUsingDefaultFile->getExtensions());

        $confIgnoringDefaultFile = (new ConfFactory)->createConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')->with('configuration')->andReturn('')
                ->shouldReceive('getOption')->with('no-configuration')->andReturn(true)
                ->shouldReceive('getOption')->with('extensions')->andReturn('')
                ->mock()
        );
        $this->assertTrue(in_array('php', $confIgnoringDefaultFile->getExtensions()));

        unlink($filename);

        $confDefaultFileMissing = (new ConfFactory)->createConf(
            m::mock('Symfony\Component\Console\Input\InputInterface')
                ->shouldReceive('getOption')->with('configuration')->andReturn('')
                ->shouldReceive('getOption')->with('no-configuration')->andReturn(false)
                ->shouldReceive('getOption')->with('extensions')->andReturn('')
                ->mock()
        );
        $this->assertTrue(in_array('php', $confDefaultFileMissing->getExtensions()));

        chdir($cwd);
    }
}
