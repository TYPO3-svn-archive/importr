<?php
namespace TYPO3\Importer\Service\Resources;
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Tim Lochmüller <tl@hdnet.de>, HDNET GmbH & Co. KG
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************  */

/**
 * Description of Excel
 *
 * @author timlochmueller
 */
class Dummy extends AbstractResource implements ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.*/";

	/**
	 * @var array
	 */
	protected $content = array();

	/**
	 * @var string
	 */
	protected $loremIpsum = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

	/**
	 * @param bool $returnAsArray
	 * @return mixed
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['records'] = isset($configuration['records']) ? (int)$configuration['records'] : 50;
		$configuration['itemsPerRecord'] = isset($configuration['itemsPerRecord']) ? (int)$configuration['itemsPerRecord'] : 5;
		return $configuration;
	}

	/**
	 *
	 * Get Random content
	 *
	 * @return int|string
	 */
	protected function getRandomContent() {
		if (rand(0, 1)) {
			return rand(0, 100) * 5;
		} else {
			$pos1 = rand(0, strlen($this->loremIpsum));
			$pos2 = rand(0, strlen($this->loremIpsum));
			if ($pos1 > $pos2)
				return substr($this->loremIpsum, $pos2, $pos1);
			else
				return substr($this->loremIpsum, $pos1, $pos2);
		}
	}

	/**
	 * @param \TYPO3\Importer\Domain\Model\Strategy $strategy
	 * @param string                            $filepath
	 */
	public function start(\TYPO3\Importer\Domain\Model\Strategy $strategy, $filepath) {

	}

	/**
	 * @return string
	 */
	public function getFilepathExpression() {
		return $this->filepathExpression;
	}

	/**
	 *
	 */
	public function parseResource() {
		$configuration = $this->getConfiguration();

		for ($i = 0; $i < $configuration['records']; $i++) {
			$record = array();

			for ($a = 0; $a < $configuration['itemsPerRecord']; $a++) {
				$record[] = $this->getRandomContent();
			}
			$this->content[] = $record;
		}
	}

	/**
	 * @return integer
	 */
	public function getAmount() {
		return count($this->content);
	}

	/**
	 * @param integer $pointer
	 * @return mixed
	 */
	public function getEntry($pointer) {
		return $this->content[$pointer];
	}

	/**
	 *
	 */
	public function end() {

	}

}