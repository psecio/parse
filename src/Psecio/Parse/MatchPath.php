<?php

namespace Psecio\Parse;

class MatchPath
{
    /**
     * Current "path" to evaluate/locate
     * @var string
     */
    private $path;

    /**
     * Matches found for the path given
     * @var array
     */
    private $matches = array();

    /**
     * Set the path for the current evaluation
     *
     * @param string $path DSL formatted path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get the current path for evaluation
     *
     * @return string Current DSL path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Add a new node object as a match
     *
     * @param object $node Node instance
     */
    public function addMatch($node)
    {
        $this->matches[] = $node;
    }

    /**
     * Get the full list of matches found
     *
     * @return array Matches set
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Starting with the node given, iterate through and find matches
     *
     * @param object $node Node instance
     * @param string $path Path to locate/evaluate
     * @param \Psecio\Parse\File $file File class instance
     */
    public function evaluate($node, $path = null, &$file)
    {
        if ($path !== null) {
            $this->setPath($path);
        }
        $path = $this->getPath();
        $parts = explode('->', $path);
        $this->iterate($node, $parts, $file, $path);
    }

    /**
     * Parse the path configuration down to pieces
     *
     * @param string $parts Configuration string
     * @return array Parsed configuration data
     */
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

    /**
     * Recursive function, handles the path evaluation
     *     and adding matches
     *
     * @param object $node Node instance
     * @param array $parts Broken down parts of config/path string
     * @param \Psecio\Parse\File $file  File instance
     * @param string $path Path to search for
     */
    public function iterate($node, $parts, &$file, $path)
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
                    $this->iterate($stmt, $parts, $file, $path);
                }
            } else {
                return $m;
            }
        } elseif ($m === true) {
            echo '...adding: '.get_class($node)."\n";
            $file->addMatch($node, $path);
        }
    }

    /**
     * Evaluate if the node given is a match for the config
     *
     * @param object $node Node instance
     * @param array $config Configuration to match against
     * @return boolean Pass/fail of match
     */
    public function isMatch($node, $config)
    {
        if (method_exists($this, $config['prefix']) === true) {
            return $this->$config['prefix']($node, $config);
        }
        return false;
    }

    /**
     * Check the "type" of the node (node class)
     *
     * @param object $node Node instance
     * @param array $data Configuration data
     * @return boolean Pass/fail of evaluation
     */
    public function type($node, $data)
    {
        $parts = explode('.', $data['type']);
        $matchClass = 'PhpParser\\Node\\'.implode('\\', $parts);

        return (stristr(get_class($node), $matchClass) !== false);
    }

    /**
     * Check the function information on node:
     *     - Name
     *     - Argument evaluation
     *
     * @param object $node Node instance
     * @param array $data Configuration data
     * @return boolean Pass/fail of function evaluation
     */
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