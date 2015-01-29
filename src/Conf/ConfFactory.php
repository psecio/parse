<?php

namespace Psecio\Parse\Conf;

use Symfony\Component\Console\Input\InputInterface;
use Psecio\Parse\File;
use SplFileInfo;

/**
 * Manage the configuration cascade
 */
class ConfFactory
{
    /**
     * Create configuration cascade
     *
     * @param  InputInterface $input         Input object
     * @param  string         &$confFileName Will contain name of used config file
     * @return Configuration
     */
    public function createConf(InputInterface $input, &$confFileName = '')
    {
        $conf = new UserConf($input);

        if ($confFileInfo = $this->getConfFileInfo($input)) {
            $confFileName = $confFileInfo->getFilename();
            $conf = new DualConf($conf, new JsonConf((new File($confFileInfo))->getContents()));
        }

        return new DualConf($conf, new DefaultConf);
    }

    /**
     * Get info on configuration file to use
     *
     * @param  InputInterface $input
     * @return SplFileInfo|void
     */
    private function getConfFileInfo(InputInterface $input)
    {
        if ($filename = $input->getOption('configuration')) {
            return new SplFileInfo($filename);
        }

        if (!$input->getOption('no-configuration')) {
            $confFileInfo = new SplFileInfo('.psecio-parse.json');
            if ($confFileInfo->isReadable()) {
                return $confFileInfo;
            }
        }
    }
}
