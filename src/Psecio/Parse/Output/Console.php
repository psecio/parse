<?php

namespace Psecio\Parse\Output;

class Console extends \Psecio\Parse\Output
{
	/**
	 * Generate the console output of the results
	 *
	 * @param array $data Scan results data
	 * @return string Formatted XML string
	 */
	public function generate(array $data)
	{
		foreach ($data as $file) {
			$ct = 1;
			$matches = $file->getMatches();
			if (count($matches) == 0) {
				continue;
			}

			echo '#### Path: '.$file->getPath()." ########\n";
			foreach ($matches as $match) {
				$node = $match['node']->getNode();
				$attrs = $node->getAttributes();

				echo '# '.$ct.' | '
					.get_class($node)." | "
					.trim(implode("\n",$file->getLines($attrs['startLine'])))
					."\n";

				$ct++;
			}
			echo "#### ---------------------\n\n";
		}
	}
}