<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/**
 * If TYPO3_version is lower than 4.7.0
 * then we need our own Scheduler Task.
 */
if (t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version) < 4007000) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Importer_Scheduler_Task'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'Command FED Task',
		'description'      => '...',
		'additionalFields' => 'Tx_Importer_Scheduler_FieldProvider',
	);
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Importer_Command_ImportCommandController';

/**
 * Two possible (and implemented signals). You can use them
 * in your own Extension, just by coping the lines.
 *
 * Dont forget to
 *
 * By default they are not connected to the slots, because they
 * can be a security risk.
 *
 * t3lib_div::makeInstance('Tx_Extbase_SignalSlot_Dispatcher')->connect('Tx_Importer_Service_Manager', 'preParseConfiguration', 'Tx_Importer_Service_SignalService', 'truncateTable');
 * t3lib_div::makeInstance('Tx_Extbase_SignalSlot_Dispatcher')->connect('Tx_Importer_Service_Manager', 'pastImport', 'Tx_Importer_Service_SignalService', 'renameFile');
 */
