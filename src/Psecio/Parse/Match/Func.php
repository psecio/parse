<?php

namespace Psecio\Parse\Match;

class Func extends \Psecio\Parse\Match
{
	public function execute($node, $config)
	{
		$matchClass = 'PhpParser\\Node\\Expr\\FuncCall';

        // If it's not a function...
        if (stristr(get_class($node), $matchClass) == false) {
            return false;
        }

        // Be sure it's named correctly
        if ((string)$node->name !== $config['type']) {
            return false;
        }

        // Do we have other checks to evaluate?
        if (isset($config['params']) && !empty($config['params'])) {
            foreach ($config['params'] as $param) {
                $paramNs = '\\Psecio\\Parse\\Param\\'.ucwords(strtolower($param['type']));
                if (!class_exists($paramNs)) {
                    throw new \InvalidArgumentException('Count not create object: '.$paramNs);
                }
                $arg = new $paramNs($config['params'], $node);
                $result = $arg->evaluate();

                // If any of our checks fail, return false
                if ($result == false) {
                    return false;
                }
            }
        }

        return true;
	}
}