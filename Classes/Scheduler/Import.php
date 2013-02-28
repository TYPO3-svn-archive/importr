<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Tim Lochmüller <tl@hdnet.de>, HDNET GmbH & Co. KG
 *  Tim Spiekerkötter <ts@hdnet.de>, HDNET GmbH & Co. KG
 *  Carsten Biebricher <cb@hdnet.de>, HDNET GmbH & Co. KG
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
 * ************************************************************* */

/**
 *
 *
 * @package importr
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class Tx_Importer_Scheduler_Import extends tx_scheduler_Task {

	/**
	 * (non-PHPdoc)
	 * @see tx_scheduler_Task::execute()
	 */
	public function execute() {
		try {
			$objectManager = new Tx_Extbase_Object_ObjectManager();
			$importer = $objectManager->get('Tx_Importer_Scheduler_ImportLogic');
			return $importer->execute($this);
		} catch (Exception $e) {
			t3lib_FlashMessageQueue::addMessage(
				t3lib_div::makeInstance('t3lib_FlashMessage', '', $e->getMessage(), t3lib_FlashMessage::ERROR)
			);
			return FALSE;
		}
	}

	/**
	 * @return integer
	 */
	public function getPid() {
		return $this->pid;
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/Importer/Classes/Scheduler/Import.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/Importer/Classes/Scheduler/Import.php']);
}
