<?php
namespace TYPO3\Importer;

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
 * Description of Utility
 *
 * @author timlochmueller
 */
class Utility {

	/**
	 * @param string $className
	 *
	 * @internal param mixed $params
	 *
	 * @return \StdClass
	 */
	public static function createObject($className) {
		$arguments = func_get_args();
		$objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
		$object = call_user_func_array(array(
		                                    $objectManager,
		                                    'get'
		                               ), $arguments);

		return $object;
	}

	/**
	 * Get TYPO3 Version
	 */
	public static function getVersion($version = NULL) {
		if ($version === NULL) {
			$version = TYPO3_version;
		}
		return \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertIntegerToVersionNumber($version);
	}

}