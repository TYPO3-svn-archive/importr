<?php
/**
 * SignalService.php
 *
 * General file information
 *
 * @category   Extension
 * @package    importer
 * @author     Tim Spiekerkoetter HDNET GmbH & Co. <tim.spiekerkoetter@hdnet.de>
 * @version    CVS: $Id:08.02.13$
 */
/**
 * SignalService.php
 *
 * General class information
 *
 * @package    importer
 * @subpackage Service
 * @author     Tim Spiekerkoetter HDNET GmbH & Co. <tim.spiekerkoetter@hdnet.de>
 */
class Tx_Importer_Service_SignalService {

	/**
	 * To rename a file from the importer you
	 * have to use the "rename: 1" statement in
	 * your configuration. The file will be
	 * prefixed with the current (human readable)
	 * timestamp.
	 *
	 * Caution: after this method, the file is moved
	 * you should only use this in the before
	 * configuration if you are fully aware of it!
	 *
	 * @param $manager
	 * @param $import
	 */
	public function renameFile($manager, $import) {
		$configuration = $import
			->getStrategy()
			->getConfiguration(TRUE);
		if (isset($configuration['after']['rename'])) {
			$oldFileName = t3lib_div::getFileAbsFileName($import->getFilepath());
			$info = pathinfo($oldFileName);
			$newFileName = $info['dirname'] . DIRECTORY_SEPARATOR . date('YmdHis') . '_' . $info['basename'];
			rename($oldFileName, $newFileName);
		}
	}

	/**
	 * To truncate a table from the importer you
	 * have to use the "truncate: " configuration.
	 * If you pass a string, then the string is
	 * interpreted as a table name. If you pass
	 * an array, every element is used as a table
	 * name.
	 *
	 * @param Tx_Importer_Service_Manager $manager
	 * @param array                       $configuration
	 */
	public function truncateTable($manager, $configuration) {
		if (isset($configuration['truncate'])) {
			if (is_array($configuration['truncate'])) {
				foreach ($configuration['truncate'] as $table) {
					$GLOBALS['TYPO3_DB']->exec_TRUNCATEquery($table);
				}
			}
			else {
				$GLOBALS['TYPO3_DB']->exec_TRUNCATEquery($configuration['truncate']);
			}
		}
	}
}
