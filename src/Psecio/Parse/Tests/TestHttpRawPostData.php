<?php

namespace Psecio\Parse\Tests;

/**
 * Don't use http_raw_post_data
 */
class TestHttpRawPostData extends \Psecio\Parse\Test
{
	protected $description = 'Avoid the use of http_raw_post_data. Deprecated and will be removed.';

	public function evaluate($node, $file = null)
	{
		$node = $node->getNode();
		if ($node instanceof \PhpParser\Node\Expr\Variable && $node->name == 'http_raw_post_data') {
			return false;
		}
	}
}