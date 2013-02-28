<?php

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
class Tx_Importer_Service_Resources_Excel extends Tx_Importer_Service_Resources_AbstractResource implements Tx_Importer_Service_Resources_ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.xls$/";

	/**
	 * @var array
	 */
	protected $content = array();

	/**
	 * @var string
	 */
	protected $filepath;

	/**
	 * @param bool $returnAsArray
	 * @return mixed
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['skipRows'] = isset($configuration['skipRows']) ? (int) $configuration['skipRows'] : 0;
		$configuration['sheet'] = isset($configuration['sheet']) ? (int) $configuration['sheet'] : -1;
		return $configuration;
	}

	/**
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @param string $filepath
	 */
	public function start(Tx_Importer_Domain_Model_Strategy $strategy, $filepath) {
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

		if (!t3lib_extMgm::isLoaded('phpexcel_library'))
			throw new Exception('phpexcel_library is not loaded', 12367812368);

		$filename = t3lib_div::getFileAbsFileName($this->filepath);
		t3lib_div::makeInstanceService('phpexcel');

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		if ($configuration['sheet'] >= 0) {
			$objWorksheet = $objPHPExcel->getSheet($configuration['sheet']);
		} else {
			$objWorksheet = $objPHPExcel->getActiveSheet();
		}

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();

		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		for ($row = 1 + $configuration['skipRows']; $row <= $highestRow; ++$row) {
			$rowRecord = array();
			for ($col = 0; $col <= $highestColumnIndex; ++$col) {
				$rowRecord[] = trim($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
			}
			$this->content[] = $rowRecord;
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