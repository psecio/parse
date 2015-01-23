<?php

namespace Psecio\Parse;

use DirectoryIterator;

/**
 * Create a RuleCollection with bundled rules
 */
class RuleFactory
{
    /**
     * @var RuleCollection The created rule collection
     */
    private $collection;

    /**
     * Optionally filter bundled rules using a whitelist and/or blacklist approach
     *
     * @param string[] $whitelist Names of rules to keep (empty array means all rules)
     * @param string[] $blacklist Names of rules to remove
     */
    public function __construct(array $whitelist = array(), array $blacklist = array())
    {
        $this->collection = new RuleCollection;
        $this->setUpCollection();
        if ($blacklist) {
            $this->blacklist($blacklist);
        }
        if ($whitelist) {
            $this->whitelist($whitelist);
        }
    }

    /**
     * Get collection of rules
     *
     * @return RuleCollection
     */
    public function createRuleCollection()
    {
        return $this->collection;
    }

    /**
     * Remove blacklisted rules from collection
     *
     * @param  string[] $names Names of rules to remove
     * @return void
     */
    public function blacklist(array $names)
    {
        foreach ($names as $ruleName) {
            $this->collection->remove($ruleName);
        }
    }

    /**
     * Remove non-whitelisted rules from collection
     *
     * @param  string[] $names Names of rules to keep
     * @return void
     */
    public function whitelist(array $names)
    {
        $oldCollection = $this->collection;
        $this->collection = new RuleCollection;
        foreach ($names as $ruleName) {
            $this->collection->add(
                $oldCollection->get($ruleName)
            );
        }
    }

    /**
     * Fill $collection with bundled rules
     *
     * @return void
     */
    private function setUpCollection()
    {
        foreach (new DirectoryIterator(__DIR__ . '/Rule') as $splFileInfo) {
            if ($splFileInfo->isDot() || $splFileInfo->isDir()) {
                continue;
            }
            $className = "\\Psecio\\Parse\\Rule\\{$splFileInfo->getBasename('.php')}";
            $this->collection->add(new $className);
        }
    }
}
