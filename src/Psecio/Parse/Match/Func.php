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

        // Check the number of arguments
        $args = null;
        foreach ($config['params'] as $param) {
            if ($param['type'] == 'args') {
                $args = $param;
            }
        }
        if ($args !== null) {
            $funcArgs = count($node->args);
            switch($args['operation']) {
                case '=':
                    if ($funcArgs !== (integer)$args['value']) {
                        return false;
                    }
                    break;
                case '>':
                    if ($funcArgs <= (integer)$args['value']) {
                        return false;
                    }
                    break;
                case '<':
                    if ($funcArgs >= (integer)$args['value']) {
                        return false;
                    }
                    break;
            }
        }
        return true;
	}
}