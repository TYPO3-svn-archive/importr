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
 * Documentation for configuration
 *
 * For configuration:
 * overwriteFilepath: XXXX
 *
 * For Resources and Targets:
 * CLASSNAME:
 *   CLASSNAME-CONFIGURATIONS (see Class)
 *
 *
 * @author timlochmueller
 */
class Tx_Importer_Domain_Model_Strategy extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 *
	 * @var string
	 */
	protected $title;

	/**
	 *
	 * @var string
	 */
	protected $configuration;

	/**
	 *
	 * @var string
	 */
	protected $resources;

	/**
	 *
	 * @var string
	 */
	protected $targets;

	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param bool $returnAsArray
	 * @return string
	 */
	public function getConfiguration($returnAsArray = FALSE) {
		if ($returnAsArray) {
			$configuration = Tx_Importer_Service_Yaml::parse($this->getConfiguration());
			$configuration['updateInterval'] = (isset($configuration['updateInterval']) && is_numeric($configuration['updateInterval'])) ? $configuration['updateInterval'] : 100;
			return $configuration;
		}
		return $this->configuration;
	}

	/**
	 * @param bool $returnAsArray
	 * @return string
	 */
	public function getResources($returnAsArray = FALSE) {
		if ($returnAsArray) {
			return Tx_Importer_Service_Yaml::parse($this->getResources());
		}
		return $this->resources;
	}

	/**
	 * @param bool $returnAsArray
	 * @return string
	 */
	public function getTargets($returnAsArray = FALSE) {
		if ($returnAsArray) {
			return Tx_Importer_Service_Yaml::parse($this->getTargets());
		}
		return $this->targets;
	}

	/**
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 *
	 * @param string $configuration
	 */
	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}

	/**
	 *
	 * @param string $resources
	 */
	public function setResources($resources) {
		$this->resources = $resources;
	}

	/**
	 *
	 * @param string $targets
	 */
	public function setTargets($targets) {
		$this->targets = $targets;
	}

}