<?php

namespace Psecio\Parse;

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
}