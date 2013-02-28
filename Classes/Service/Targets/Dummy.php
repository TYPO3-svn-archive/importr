<?php

/*
 *   Copyright notice
 * 
 *   (c) 2011 Tim Lochmüller <tl@hdnet.de>, HDNET GmbH & Co. KG
 * 
 *   All rights reserved
 * 
 *   This script is part of the TYPO3 project. The TYPO3 project is
 *   free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *   The GNU General Public License can be found at
 *   http://www.gnu.org/copyleft/gpl.html.
 * 
 *   This script is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 * 
 *   This copyright notice MUST APPEAR in all copies of the script!
 */
/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class Tx_Importer_Service_Targets_Dummy extends Tx_Importer_Service_Targets_AbstractTarget implements Tx_Importer_Service_Targets_TargetInterface {

	/**
	 * @var Tx_Extbase_Object_Manager
	 */
	protected $objectManager;

	/**
	 * @return array
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['sleepSeconds'] = (isset($configuration['sleepSeconds'])) ? (int)$configuration['sleepSeconds'] : 1;
		$configuration['result'] = (isset($configuration['result'])) ? (int)$configuration['result'] : 'unsure';
		return $configuration;
	}

	/**
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @return void
	 */
	public function start(Tx_Importer_Domain_Model_Strategy $strategy) {

	}

	/**
	 * @param array $entry
	 * @return int|void
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		if ($configuration['sleepSeconds'] > 0)
			sleep($configuration['sleepSeconds']);

		// Return
		$results = array(
			'ignored', 'insert', 'error', 'unsure', 'update'
		);
		if ($configuration['result'] == 'random') {
			$configuration['result'] = $results[rand(0, sizeof($results) - 1)];
		}

		switch ($configuration['result']) {
			case 'ignored':
				return Tx_Importer_Service_Targets_TargetInterface::RESULT_IGNORED;
			case 'insert':
				return Tx_Importer_Service_Targets_TargetInterface::RESULT_INSERT;
			case 'error':
				return Tx_Importer_Service_Targets_TargetInterface::RESULT_ERROR;
			case 'unsure':
				return Tx_Importer_Service_Targets_TargetInterface::RESULT_UNSURE;
			case 'update':
				return Tx_Importer_Service_Targets_TargetInterface::RESULT_UPDATE;
			default:
				throw new Exception('Invalid result param "' . $configuration['result'] . '". Have to be one of: ' . var_export($results, TRUE), 12617283);

		}

	}

	public function end() {
	}
}
