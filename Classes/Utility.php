<?php
namespace TYPO3\Importr;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
		$objectManager = new ObjectManager();
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
		return VersionNumberUtility::convertIntegerToVersionNumber($version);
	}

}