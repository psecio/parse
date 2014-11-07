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

    public function iterate($node, $parts, &$file, $path)
    {
        $config = explode(':', $parts[0]);
        $m = $this->isMatch($node, $config);

        if ($m === true) {
            $parts = array_values(array_slice($parts, 1));
        }

        if (count($parts) > 0) {
            $found = false;
            $stmts = $node->stmts;
            if (!empty($stmts)) {
                foreach ($stmts as $stmt) {
                    if ($found == true) { continue; }
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
        if (method_exists($this, $config[0]) === true) {
            return $this->$config[0]($node, $config);
        }
        return false;
    }

    public function type($node, $data)
    {
        $parts = explode('.', $data[1]);
        $matchClass = 'PhpParser\\Node\\'.implode('\\', $parts);

        return (stristr(get_class($node), $matchClass) !== false);
    }
}