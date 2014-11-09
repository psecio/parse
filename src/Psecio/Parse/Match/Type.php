<?php

namespace Psecio\Parse\Match;

class Type extends \Psecio\Parse\Match
{
	public function execute($node, $config)
	{
		$parts = explode('.', $config['type']);
        $matchClass = 'PhpParser\\Node\\'.implode('\\', $parts);

        return (stristr(get_class($node), $matchClass) !== false);
	}
}