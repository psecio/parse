<?php

namespace Psecio\Parse\Rule;

use Psecio\Parse\RuleInterface;
use PhpParser\Parser;
use PhpParser\Lexer\Emulative as Lexer;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

/**
 * Base test for implementing full-parse based unit tests
 */
abstract class RuleTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser The parser to use to parse samples
     */
    protected $parser;

    /**
     * Set up the parser the same way the Scanner does
     */
    public function setUp()
    {
        $this->parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * Method to create the test to evaluate
     *
     * This should return an instantiated object of the class that is being evaluated
     *
     * @return RuleInterface An object of the type being tested
     */
    abstract protected function buildTest();

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
    public function parseSampleProvider()
    {
        return [];
    }

    /**
     * Run the tests supplied by {@see parseSampleProvider()}
     */
    public function testParseSample()
    {
        foreach ($this->parseSampleProvider() as $index => $args) {
            list($code, $result) = $args;
            $this->assertParseTest(
                $result,
                $code,
                sprintf('Sample #%d from %s::parseSampleProvider failed.', $index, get_class($this))
            );
        }
    }

    public function testDescription()
    {
        $this->assertInternalType(
            'string',
            $this->buildTest()->getDescription(),
            'getDescription() must return a string'
        );
    }

    public function testLongDescription()
    {
        $this->assertInternalType(
            'string',
            $this->buildTest()->getLongDescription(),
            'getLongDescription() must return a string'
        );
    }

    /**
     * Assert that running $test against $code results in $expected
     *
     * Note taht $message does not replace the message from the assertion,
     * only augments it.
     *
     * @param string  $code      The PHP code to parse and evaulate
     * @param mixed   $expected  The expected result of the $test
     * @param string  $message   Message to be displayed on failure
     */
    public function assertParseTest($expected, $code, $message = '')
    {
        $message = sprintf(
            "%sThe parser scan should have %s the test.\nTested code was:\n%s",
            empty($message) ? '' : ($message . "\n"),
            $expected ? 'passed' : 'failed',
            $this->formatCodeForMessage($code)
        );

        $actual = $this->scan($code);

        $this->assertSame($expected, $actual, $message);
    }

    /**
     * Assert that parsing $code with buildTest()'s Test returns false
     *
     * @param string $code     Code to test
     * @param string $message  Message to display on failure
     */
    public function assertParseTestFalse($code, $message = '')
    {
        $this->assertParseTest(false, $code, $message);
    }

    /**
     * Assert that parsing $code with buildTest()'s Test returns true
     *
     * @param string $code     Code to test
     * @param string $message  Message to display on failure
     */
    public function assertParseTestTrue($code, $message = '')
    {
        $this->assertParseTest(true, $code, $message);
    }

    /**
     * Format a code string so it displays nicely in an assertion message
     *
     * Prefixes each line of the code with " > ".
     *
     * @param string $code  The code to format
     *
     * @return string  The formatted code
     */
    protected function formatCodeForMessage($code)
    {
        $linePrefix = " > ";
        return $linePrefix . str_replace("\n", "\n" . $linePrefix, trim($code));
    }

    /**
     * Scan PHP code and return the result
     *
     * @param string $code  The code to scan
     *
     * @return bool  The results visiting all the nodes from the parsed $code
     */
    protected function scan($code)
    {
        $visitor = new RuleTestVisitor($this->buildTest());
        $traverser = new NodeTraverser;
        $traverser->addVisitor($visitor);
        $traverser->traverse($this->parser->parse('<?php ' . $code));

        return $visitor->result;
    }
}
