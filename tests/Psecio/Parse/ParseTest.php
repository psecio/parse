<?php

namespace tests\Psecio\Parse;

require 'ParseTestVisitor.php';

/**
 * Base test for implementing full-parse based unit tests
 */
abstract class ParseTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PhpParser\Parser  The parser to use to parse samples */
    protected $parser;

    public function setUp()
    {
        // Set up the parser the same way the Scanner does.
        $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer\Emulative);
    }

    /**
     * PHPUnit provider to provide samples and results
     *
     * This method provides a list of samples and expected results that this unit test
     * should test against. The structure should be:
     *
     *    [ ['valid php code', <expected result>],
     *      [ ... ],
     *      ...
     *    ]
     *
     * Note that the actual test method prefixes the sample code with '<?php ' to make
     * the samples more consise.
     *
     * @return array  Lists of samples to test against
     */
    abstract public function parseSampleProvider();

    /**
     * Method to create the Test to evaluate
     *
     * This should return an instantiated object of the class that is being evaluated
     *
     * @return \Psecio\Parse\Test  An object of the type being tested
     */
    abstract protected function buildTest();

    /**
     * @dataProvider parseSampleProvider
     */
    public function test_parseSample($code, $result)
    {
        $this->evalTest($this->buildTest(), $code, $result);
    }

    /**
     * Run the Test and assert its correctness
     *
     * @param \Psecio\Parse\Test $test      The test to evaluate
     * @param string             $code      The PHP code to parse and evaulate
     * @param mixed              $expected  The expected result of the $test
     */
    protected function evalTest($test, $code, $expected)
    {
        // This should evalute things in much the same way as the Scanner.
        $visitor = new ParseTestVisitor($test);
        $traverser = new \PhpParser\NodeTraverser;
        $traverser->addVisitor($visitor);
        $statements = $this->parser->parse('<?php ' . $code);
        $traverser->traverse($statements);

        $this->assertEquals($expected, $visitor->result);
    }
}
