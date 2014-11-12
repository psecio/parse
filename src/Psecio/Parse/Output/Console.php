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
		// print_r($data);

		foreach ($data as $file) {
			echo '#### Path: '.$file->getPath()." ########\n";

			$ct = 1;
			foreach ($file->getMatches() as $match) {
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