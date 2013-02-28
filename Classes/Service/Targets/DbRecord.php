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
 * Description of Tx_Importer_Service_Targets_DbRecord
 *
 * @author timlochmueller
 */
class Tx_Importer_Service_Targets_DbRecord extends Tx_Importer_Service_Targets_AbstractTarget implements Tx_Importer_Service_Targets_TargetInterface {
	/**
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 */
	public function start(Tx_Importer_Domain_Model_Strategy $strategy) {
		
	}
	
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['pid'] = (isset($configuration['pid']) && is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;
		
		return $configuration;
	}
	
	/**
	 * @param array $entry
	 * @return integer
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		$mapping = $configuration['mapping'];
		
		$insertFields = array();
		foreach ($mapping as $key => $value) {
			$insertFields[$value] = $entry[$key];
		}
		
		$insertFields['pid'] = $configuration['pid'];
		
		$GLOBALS['TYPO3_DB']->exec_INSERTquery($configuration['table'], $insertFields);
		return Tx_Importer_Service_Targets_TargetInterface::RESULT_UNSURE;
	}
	/**
	 * 
	 */
	public function end() {
		
	}
}