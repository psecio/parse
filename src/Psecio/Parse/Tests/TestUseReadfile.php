<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use readfile, readlink or readgzfile - they output content directly
 */
class TestUseReadfile extends \Psecio\Parse\Test
{
	protected $description = 'The readfile/readlink/readgzfile functions output content directly (possible injection)';

	public function evaluate($node, $file = null)
	{
		if (
			$node->isFunction('readfile')
			|| $node->isFunction('readlink')
			|| $node->isFunction('readgzfile')
		) {
			return false;
		}

		return true;
	}
}