<?php

namespace Psecio\Parse;

class FileTest extends \PHPUnit_Framework_TestCase
{
	private $file;
	private $path;

	public function setUp()
	{
		$this->path = __DIR__.'/sample-file.php';
		$this->file = new File($this->path);
	}
	public function tearDown()
	{
		unset($this->file);
	}

	/**
	 * Test the getting/setting of the file path
	 */
	public function testGetSetPath()
	{
		$this->assertEquals(
			$this->path,
			$this->file->getPath()
		);
	}

	/**
	 * Test that the content from the file is fetched correctly
	 */
	public function testGetContent()
	{
		$this->assertEquals(
			"<?php echo 'sample-file.php'; ?>",
			$this->file->getContents()
		);
	}

	/**
	 * Test the setting of different content
	 */
	public function testSetContent()
	{
		$newContent = 'this is a test';
		$this->file->setContents($newContent);

		$this->assertEquals(
			$this->file->getContents(),
			$newContent
		);
	}

	/**
	 * Test the "get lines" funcitonality
	 */
	public function testGetLines()
	{
		$newContent = "this is\na test with\nnewlines\nhere";
		$this->file->setContents($newContent);

		// A single line w/o optional param
		$lines = $this->file->getLines(2);

		$this->assertTrue(is_array($lines) && !empty($lines));
		$this->assertEquals(
			$lines,
			array('a test with')
		);

		// Multiple lines with second param
		$this->assertEquals(
			$this->file->getLines(2, 5),
			array('a test with', 'newlines', 'here')
		);
	}

	/**
	 * Test the getter/setter for matches on the file
	 */
	public function testGetSetMatches()
	{
		$matches = array('foo', 'bar');
		$this->file->setMatches($matches);

		$this->assertEquals(
			$this->file->getMatches(),
			$matches
		);
	}
}