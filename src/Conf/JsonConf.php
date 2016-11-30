<?php

namespace Psecio\Parse\Conf;

use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use RuntimeException;
use Symfony\Component\Finder\Finder;

/**
 * Json configuration wrapper
 */
class JsonConf implements Configuration
{
    /**
     * @var object Loaded configurations
     */
    private $data;

    /**
     * Load configurations from json encoded string
     *
     * @param  string $json
     * @throws RuntimeException If json is not valid
     */
    public function __construct($json)
    {
        $this->data = json_decode($json);

        $retriever = new UriRetriever;
        $schema = $retriever->retrieve('file://' . realpath(__DIR__ . '/schema.json'));

        $validator = new Validator;
        $validator->check($this->data, $schema);

        foreach ($validator->getErrors() as $error) {
            throw new RuntimeException("Invalid configuration for {$error['property']}\n{$error['message']}");
        }
    }

    public function getFormat()
    {
        return $this->read('format', '');
    }

    public function getPaths()
    {
        $finder = (new Finder())->directories();
        foreach ($this->read('paths', []) as $path) {
            $finder->in($path);
        }
        $out = [];
        foreach ($finder as $path) {
          $out[] = $path;
        }
        return $out;
    }

    public function getIgnorePaths()
    {
        return $this->read('ignore-paths', []);
    }

    public function getExtensions()
    {
        return $this->read('extensions', []);
    }

    public function getRuleWhitelist()
    {
        return $this->read('whitelist-rules', []);
    }

    public function getRuleBlacklist()
    {
        return $this->read('blacklist-rules', []);
    }

    public function disableAnnotations()
    {
        return $this->read('disable-annotations', false);
    }

    /**
     * Read configuration options
     *
     * @param  string $property Name of property to read
     * @param  mixed  $default  Returned if otion is not set
     * @return mixed
     */
    private function read($property, $default)
    {
        if (property_exists($this->data, $property)) {
            return $this->data->$property;
        }
        return $default;
    }
}
