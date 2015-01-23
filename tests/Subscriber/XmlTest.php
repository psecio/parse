<?php

namespace Psecio\Parse\Subscriber;

use Mockery as m;

class XmlTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateXml()
    {
        $xmlStr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<results>
 <error>
  <description>error description</description>
  <file>/error/path</file>
 </error>
 <issue>
  <type>RuleName</type>
  <description>issue description</description>
  <file>/issue/path</file>
  <line>1</line>
  <source><![CDATA[php source]]></source>
 </issue>
</results>
";

        $output = m::mock('\Symfony\Component\Console\Output\OutputInterface');

        $output->shouldReceive('writeln')->once()->with(
            $xmlStr,
            \Symfony\Component\Console\Output\OutputInterface::OUTPUT_RAW
        );

        $xml = new Xml($output);

        $xml->onScanStart();

        $errorEvent = m::mock('\Psecio\Parse\Event\ErrorEvent');
        $errorEvent->shouldReceive('getMessage')->once()->andReturn('error description');
        $errorEvent->shouldReceive('getFile->getPath')->once()->andReturn('/error/path');

        $xml->onFileError($errorEvent);

        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('getPath')->once()->andReturn('/issue/path');
        $file->shouldReceive('fetchNode')->once()->andReturn(['php source']);

        $issueEvent = m::mock('\Psecio\Parse\Event\IssueEvent');
        $issueEvent->shouldReceive('getNode')->atLeast(1)->andReturn(
            m::mock('PhpParser\Node')->shouldReceive('getLine')->atLeast(1)->andReturn(1)->mock()
        );
        $issueEvent->shouldReceive('getRule->getName')->once()->andReturn('RuleName');
        $issueEvent->shouldReceive('getRule->getDescription')->once()->andReturn('issue description');
        $issueEvent->shouldReceive('getFile')->zeroOrMoreTimes()->andReturn($file);

        $xml->onFileIssue($issueEvent);

        $xml->onScanComplete();
    }
}
