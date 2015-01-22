<?php

namespace Psecio\Parse\DocComment;

class DocCommentTest extends \PHPUnit_Framework_TestCase
{
    const DOC_BLOCK = <<<'EOF'
/**
 * Multiline
 * summary
 *
 * Body
 *
 * Spanning multiple
 * paragrafs
 *
 * @tagOne content
 *  @tagOne   second tag one
 * @multilineTag content
 * on multiple lines
 *
 * @emptyTag
 *
 * This line is ignored
 * @namespaced\tag foobar
 *
 * This line is ignored
 */
EOF;

    public function testTags()
    {
        $this->assertSame(
            ['name' => ['content']],
            (new DocComment("/** @name content */"))->getTags()
        );
        $this->assertSame(
            ['name' => ['content']],
            (new DocComment("# @name content"))->getTags()
        );
        $this->assertSame(
            ['name' => ['content']],
            (new DocComment("// @name content"))->getTags()
        );
        $this->assertSame(
            [
                'tagOne' => ['content', 'second tag one'],
                'multilineTag' => ['content on multiple lines'],
                'emptyTag' => [''],
                'namespaced\\tag' => ['foobar']
            ],
            (new DocComment(self::DOC_BLOCK))->getTags()
        );
    }

    public function testSummary()
    {
        $this->assertSame(
            'summary',
            (new DocComment('// summary'))->getSummary()
        );
        $this->assertSame(
            'summary',
            (new DocComment(' //summary '))->getSummary()
        );
        $this->assertSame(
            'summary',
            (new DocComment('# summary'))->getSummary()
        );
        $this->assertSame(
            'summary',
            (new DocComment('/* summary */'))->getSummary()
        );
        $this->assertSame(
            'summary',
            (new DocComment('/** summary */'))->getSummary()
        );
        $this->assertSame(
            'Multiline summary',
            (new DocComment(self::DOC_BLOCK))->getSummary()
        );
    }

    public function testBody()
    {
        $this->assertSame(
            "Body\n\nSpanning multiple\nparagrafs",
            (new DocComment(self::DOC_BLOCK))->getBody()
        );
    }

    public function testGetMatchingTags()
    {
        $block = <<<EOD
/**
 * Summary
 *
 * @tag1 value1
 * @tag2 value2
 * @tag1 value3
 * @tag1 value4
 * @tag2 value5
 * @Tag1 valueA
 */
EOD;
        $expectedTag1 = ['value1', 'value3', 'value4'];
        $expectedTag2 = ['value2', 'value5'];
        $dc = new DocComment($block);

        $this->assertEquals($expectedTag1, $dc->getMatchingTags('tag1'));
        $this->assertEquals($expectedTag2, $dc->getMatchingTags('tag2'));
        $this->assertEquals([], $dc->getMatchingTags('notATag'));
    }

    public function testGetIMatchingTags()
    {
        $block = <<<EOD
/**
 * Summary
 *
 * @tag1 value1
 * @Tag1 value3
 * @TAG1 value4
 */
EOD;
        $expectedTag1 = ['value1', 'value3', 'value4'];
        $dc = new DocComment($block);

        $this->assertEquals($expectedTag1, $dc->getIMatchingTags('tag1'));
        $this->assertEquals([], $dc->getIMatchingTags('notATag'));
    }

    public function testRawComment()
    {
        $doc = new DocComment(self::DOC_BLOCK);
        $this->assertSame(self::DOC_BLOCK, $doc->getRawComment());
    }
}