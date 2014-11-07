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
        $parts = explode('>', $path);
        return $this->iterate($node, $parts, $file, $path);
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
                $p = explode('=', $option);
                $params[$p[0]] = $p[1];
            }
        }

        $config = array(
            'prefix' => $config[0],
            'type' => $config[1],
            'params' => $params
        );

        return $config;
    }

    public function iterate($node, $parts, &$file, $path)
    {
        $config = $this->formatConfig($parts[0]);
        // print_r($config);

        $m = $this->isMatch($node, $config);

        if ($m === true) {
            $parts = array_values(array_slice($parts, 1));
        }

        if (count($parts) > 0) {
            $found = false;
            $stmts = $node->stmts;
            if (!empty($stmts)) {
                foreach ($stmts as $stmt) {
                    $found = $this->iterate($stmt, $parts, $file, $path);
                }
            } else {
                return $m;
            }
            return $found;
        } else {
            $file->addMatch($node, $path);
            return $m;
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

        if (stristr(get_class($node), $matchClass) !== false && (string)$node->name !== $data['type']) {
            return false;
        }

        if (isset($data['params']['args']) && count($node->args) !== (integer)$data['params']['args']) {
            return false;
        }
        return true;
    }
}