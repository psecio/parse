<?php

namespace Psecio\Parse;

class MatchPath
{
    private $path;
    private $matches = array();

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function addMatch($node)
    {
        $this->matches[] = $node;
    }

    public function getMatches()
    {
        return $this->matches;
    }

    public function evaluate($node, $path = null, &$file)
    {
        if ($path !== null) {
            $this->setPath($path);
        }
        $path = $this->getPath();
        $parts = explode('->', $path);
        $lvl = 0;
        return $this->iterate($node, $parts, $file, $path, $lvl);
    }

    public function formatConfig($parts)
    {
        $params = array();
        $config = explode(':', $parts);
        preg_match_all('/\[(.+)\]/', $config[1], $matches);

        if (!empty($matches[0])) {
            $config[1] = str_replace($matches[0][0], '', $config[1]);
            $options = explode('&', $matches[1][0]);

            foreach ($options as $option) {
                preg_match('/(.+?)([=<>!]+)(.+?)/', $option, $matches);

                $param = array(
                    'type' => $matches[1],
                    'operation' => $matches[2],
                    'value' => $matches[3]
                );

                // See if we have options on the "type"
                if (preg_match('/\((.+)\)/', $matches[1], $opt) > 0) {
                    $param['options'] = array_slice($opt, 1);
                }

                $params[] = $param;
            }
        }

        $config = array(
            'prefix' => $config[0],
            'type' => $config[1],
            'params' => $params
        );

        return $config;
    }

    public function iterate($node, $parts, &$file, $path, $lvl)
    {
        $config = $this->formatConfig($parts[0]);
        $m = $this->isMatch($node, $config);

        if ($m === true) {
            $parts = array_values(array_slice($parts, 1));
        }

        if (count($parts) > 0) {
            $stmts = $node->stmts;
            if (!empty($stmts)) {
                foreach ($stmts as $stmt) {
                    $this->iterate($stmt, $parts, $file, $path, $lvl);
                }
            } else {
                return $m;
            }
        } elseif ($m === true) {
            echo '...adding: '.get_class($node)."\n";
            $file->addMatch($node, $path);
        }
    }

    public function isMatch($node, $config)
    {
        if (method_exists($this, $config['prefix']) === true) {
            return $this->$config['prefix']($node, $config);
        }
        return false;
    }

    public function type($node, $data)
    {
        $parts = explode('.', $data['type']);
        $matchClass = 'PhpParser\\Node\\'.implode('\\', $parts);

        return (stristr(get_class($node), $matchClass) !== false);
    }

    public function func($node, $data)
    {
        $matchClass = 'PhpParser\\Node\\Expr\\FuncCall';

        // If it's not a function...
        if (stristr(get_class($node), $matchClass) == false) {
            return false;
        }

        // Be sure it's named correctly
        if ((string)$node->name !== $data['type']) {
            return false;
        }

        // Check the number of arguments
        $args = null;
        foreach ($data['params'] as $param) {
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