<?php

namespace Psecio\Parse\Output;

class Xml extends \Psecio\Parse\Output
{
	/**
	 * Generate the XML markup of the results
	 *
	 * @param array $data Scan results data
	 * @return string Formatted XML string
	 */
	public function generate(array $data)
	{
		$xml = new \DOMDocument('1.0', 'UTF-8');
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;

		$results = $xml->createElement('results');

		foreach ($data as $file) {
			$f = $xml->createElement('file');
			$f->setAttribute('path', $file->getPath());

			$matches = $xml->createElement('matches');

			foreach ($file->getMatches() as $match) {
				$m = $xml->createElement('match');
				$m->setAttribute('path', $match['path']);

				$node = $match['node'];
				$n = $xml->createElement('node');
				$n->setAttribute('type', get_class($node));

				$matches->appendChild($m);
			}
			$f->appendChild($matches);

			$c = $xml->createElement('contents');
			$contents = $xml->createCDATASection($file->getContents());
			$c->appendChild($contents);
			$f->appendChild($c);

			$results->appendChild($f);
		}

		$xml->appendChild($results);
		return $xml->saveXML();
	}
}