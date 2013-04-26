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
 * Description of Csv
 *
 * Tx_Importer_Service_Resources_Csv:
 *  [length: 1000]
 *  [delimiter: ,]
 *  [enclosure: "]
 *  [escape: \]
 *
 * @author timlochmueller
 */
class Csv extends AbstractResource implements ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.csv$/";

	/**
	 * @var array
	 */
	protected $content = array();

	/**
	 * @var string
	 */
	protected $filepath;

	/**
	 * @return mixed
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['length'] = (isset($configuration['length']) && is_numeric($configuration['length'])) ? $configuration['length'] : 1000;
		$configuration['delimiter'] = isset($configuration['delimiter']) ? $configuration['delimiter'] : ';';
		$configuration['enclosure'] = isset($configuration['enclosure']) ? $configuration['enclosure'] : '"';
		$configuration['escape'] = isset($configuration['escape']) ? $configuration['escape'] : '\\';
		$configuration['skipRows'] = isset($configuration['skipRows']) ? $configuration['skipRows'] : '0';
		return $configuration;
	}

	/**
	 * @param \TYPO3\Importer\Domain\Model\Strategy $strategy
	 * @param string                                $filepath
	 */
	public function start(\TYPO3\Importer\Domain\Model\Strategy $strategy, $filepath) {
		$this->filepath = $filepath;
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
		ini_set('auto_detect_line_endings', TRUE);
		if (($handle = fopen(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->filepath), "r")) !== FALSE) {
			$row = 0;
			while (($buffer = fgetcsv($handle, $configuration['length'], $configuration['delimiter'], $configuration['enclosure'], $configuration['escape'])) !== FALSE) {
				if ($row < $configuration['skipRows']) {
					$row++;
					continue;
				}

				$this->content[] = $buffer;
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
	}

	/**
	 * @return integer
	 */
	public function getAmount() {
		return count($this->content);
	}

	/**
	 * @param integer $pointer
	 *
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