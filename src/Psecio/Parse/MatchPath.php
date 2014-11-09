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
            // We have options to parse!
            $options = explode('&', $matches[1][0]);

            foreach ($options as $opt) {
                $tmp = array(
                    'original' => $opt,
                    'options' => array()
                );
                preg_match('/(.+?)\{(.+?)\}/', $opt, $optionMatches);

                $tmp['type'] = $optionMatches[1];
                $parts = explode(',', $optionMatches[2]);

                foreach ($parts as $part) {
                    preg_match('/(.*?)([=<>])(.+)/', $part, $sections);
                    $sections[1] = (empty($sections[1])) ? 'eval' : $sections[1];

                    // See if we need to split up the value
                    if (preg_match('/\((.+?)\)(.+)/', $sections[3], $valueMatches) == 1){
                        $sections[3] = array(
                            'type' => $valueMatches[1],
                            'value' => $valueMatches[2]
                        );
                    }

                    $sections = array(
                        'param' => $sections[1],
                        'operation' => $sections[2],
                        'value' => $sections[3]
                    );
                    $tmp['options'][] = $sections;
                }
                $params[] = $tmp;
            }

            $config[1] = str_replace($matches[0][0], '', $config[1]);
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
        $matchClass = 'Psecio\\Parse\\Match\\'.ucwords(strtolower($config['prefix']));
        if (class_exists($matchClass)) {
            $match = new $matchClass();
            return $match->execute($node, $config);
        }
        return false;
    }
}