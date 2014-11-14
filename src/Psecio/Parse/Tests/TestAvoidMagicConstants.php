<?php

namespace Psecio\Parse\Tests;

/**
 * Avoid the use of the magic constants __DIR__ and __FILE__
 */
class TestAvoidMagicConstants extends \Psecio\Parse\Test
{
	protected $description = 'Avoid the use of magic constants like __DIR__ & __FILE__';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();
		if ($node instanceof \PhpParser\Node\Scalar\MagicConst\Dir) {
			return false;
		}
		if ($node instanceof \PhpParser\Node\Scalar\MagicConst\File) {
			return false;
		}
		return true;
	}
}