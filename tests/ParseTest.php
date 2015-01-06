<?php

namespace Psecio\Parse;

use Psecio\Parse\TestInterface;
use PhpParser\Parser;
use PhpParser\Lexer\Emulative as Lexer;
use PhpParser\NodeTraverser;

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
        $this->parser = new Parser(new Lexer);
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
        $this->assertParseTestResult($this->buildTest(), $code, $result);
    }

    /**
     * Assert that parsing $code with buildTest()'s Test returns false
     *
     * @param string $code     Code to test
     * @param string $message  Message to display on failure
     */
    public function assertParseTestFalse($code, $message = '')
    {
        $this->assertParseTestResult($this->buildTest(), $code, false, $message);
    }

    /**
     * Assert that parsing $code with buildTest()'s Test returns true
     *
     * @param string $code     Code to test
     * @param string $message  Message to display on failure
     */
    public function assertParseTestTrue($code, $message = '')
    {
        $this->assertParseTestResult($this->buildTest(), $code, true, $message);
    }

    /**
     * Assert that running $test against $code results in $expected
     *
     * @param \Psecio\Parse\Test $test      The test to evaluate
     * @param string             $code      The PHP code to parse and evaulate
     * @param mixed              $expected  The expected result of the $test
     * @param string             $message   Message to be displayed on failure
     */
    public function assertParseTestResult(TestInterface $test, $code, $expected, $message = '')
    {
        // This should evalute things in much the same way as the Scanner.
        $visitor = new ParseTestVisitor($test);
        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor);
        $statements = $this->parser->parse('<?php ' . $code);
        $traverser->traverse($statements);

        $constraint = new \PHPUnit_Framework_Constraint_IsEqual($expected, 0, 10, false, false);
        self::assertThat($visitor->result, $constraint, $message);
    }
}
