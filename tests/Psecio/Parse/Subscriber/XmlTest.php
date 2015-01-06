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
  <type>TestName</type>
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

        $messageEvent = m::mock('\Psecio\Parse\Event\MessageEvent');
        $messageEvent->shouldReceive('getMessage')->once()->andReturn('error description');
        $messageEvent->shouldReceive('getFile->getPath')->once()->andReturn('/error/path');

        $xml->onFileError($messageEvent);

        $file = m::mock('\Psecio\Parse\File');
        $file->shouldReceive('getPath')->once()->andReturn('/issue/path');
        $file->shouldReceive('getLines')->once()->with('1')->andReturn(['php source']);

        $issueEvent = m::mock('\Psecio\Parse\Event\IssueEvent');
        $issueEvent->shouldReceive('getNode->getAttributes')->once()->andReturn(['startLine' => '1']);
        $issueEvent->shouldReceive('getTest->getName')->once()->andReturn('TestName');
        $issueEvent->shouldReceive('getTest->getDescription')->once()->andReturn('issue description');
        $issueEvent->shouldReceive('getFile')->zeroOrMoreTimes()->andReturn($file);

        $xml->onFileIssue($issueEvent);

        $xml->onScanComplete();
    }

    public function testSubscription()
    {
        $this->assertInternalType(
            'array',
            Xml::getSubscribedEvents()
        );
    }
}
