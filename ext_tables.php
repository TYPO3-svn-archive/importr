<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::registerModule(
	$_EXTKEY, 'file', 'tx_importer_mod', '', array(
		'Importer' => 'index,import,preview,create',
	), array(
		'access' => 'user,group',
		'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
		'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml'
	)
);
$TCA['tx_importer_domain_model_import'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:importer/Resources/Private/Language/locallang.xml:tx_importer_domain_model_import',
		'label' => 'starttime',
		'label_alt' => 'filepath',
		'label_alt_force' => 1,
		'searchFields' => 'filepath',
		'rootLevel' => 1,
		'default_sortby' => 'ORDER BY starttime',
		'delete' => 'deleted',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/Import.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Import.png',
	),
	'interface' => array(
		'showRecordFieldList' => ''
	)
);
$TCA['tx_importer_domain_model_strategy'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:importer/Resources/Private/Language/locallang.xml:tx_importer_domain_model_strategy',
		'label' => 'title',
		'searchFields' => 'title',
		'rootLevel' => 1,
		'delete' => 'deleted',
		'default_sortby' => 'ORDER BY title',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/Tca/Strategy.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Strategy.png',
	),
	'interface' => array(
		'showRecordFieldList' => ''
	)
);
