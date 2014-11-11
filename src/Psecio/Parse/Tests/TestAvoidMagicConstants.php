<?php

namespace Psecio\Parse\Tests;

/**
 * Avoid the use of the magic constants __DIR__ and __FILE__
 */
class TestAvoidMagicConstants extends \Psecio\Parse\Test
{
	public function evaluate($node, $file = null)
	{
		if ($node instanceof \PhpParser\Node\Scalar\MagicConst\Dir) {
			return false;
		}
		if ($node instanceof \PhpParser\Node\Scalar\MagicConst\File) {
			return false;
		}
		return true;
	}
}