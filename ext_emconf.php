<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "importer".
 *
 * Auto generated 26-02-2013 09:30
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Importer',
	'description' => 'Flexible importer for all kinds of files!',
	'category' => 'be',
	'shy' => 0,
	'version' => '4.7.8',
	'dependencies' => 'extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Tim Lochmüller, Tim Spiekerkötter',
	'author_email' => 'tl@hdnet.de, ts@hdnet.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.4.0-0.0.0',
			'fluid' => '1.4.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:77:{s:12:"ext_icon.gif";s:4:"9441";s:17:"ext_localconf.php";s:4:"c483";s:14:"ext_tables.php";s:4:"8204";s:14:"ext_tables.sql";s:4:"7755";s:19:"Classes/Utility.php";s:4:"11b7";s:43:"Classes/Command/ImportCommandController.php";s:4:"a1a4";s:41:"Classes/Controller/ImporterController.php";s:4:"37ef";s:31:"Classes/Domain/Model/Import.php";s:4:"aa85";s:33:"Classes/Domain/Model/Strategy.php";s:4:"31da";s:46:"Classes/Domain/Repository/ImportRepository.php";s:4:"c469";s:48:"Classes/Domain/Repository/StrategyRepository.php";s:4:"175c";s:43:"Classes/Exception/ReinitializeException.php";s:4:"0b54";s:42:"Classes/Exception/StartImportException.php";s:4:"82b9";s:35:"Classes/Scheduler/FieldProvider.php";s:4:"bfa2";s:28:"Classes/Scheduler/Import.php";s:4:"63e1";s:33:"Classes/Scheduler/ImportLogic.php";s:4:"4c63";s:26:"Classes/Scheduler/Task.php";s:4:"4214";s:27:"Classes/Service/Manager.php";s:4:"b297";s:33:"Classes/Service/SignalService.php";s:4:"e8c1";s:24:"Classes/Service/Yaml.php";s:4:"05bd";s:46:"Classes/Service/Resources/AbstractResource.php";s:4:"542f";s:33:"Classes/Service/Resources/Csv.php";s:4:"729a";s:35:"Classes/Service/Resources/Dummy.php";s:4:"374e";s:35:"Classes/Service/Resources/Excel.php";s:4:"c511";s:47:"Classes/Service/Resources/ResourceInterface.php";s:4:"af89";s:42:"Classes/Service/Targets/AbstractTarget.php";s:4:"9918";s:36:"Classes/Service/Targets/DbRecord.php";s:4:"3c63";s:33:"Classes/Service/Targets/Dummy.php";s:4:"5c98";s:40:"Classes/Service/Targets/ExtbaseModel.php";s:4:"b3f8";s:43:"Classes/Service/Targets/TargetInterface.php";s:4:"2417";s:38:"Classes/ViewHelpers/JsonViewHelper.php";s:4:"254b";s:28:"Configuration/Tca/Import.php";s:4:"e164";s:30:"Configuration/Tca/Strategy.php";s:4:"687e";s:40:"Resources/Private/Language/locallang.xml";s:4:"2434";s:44:"Resources/Private/Language/locallang_mod.xml";s:4:"f095";s:38:"Resources/Private/Layouts/Backend.html";s:4:"71c1";s:40:"Resources/Private/Php/Yaml/composer.json";s:4:"3f84";s:37:"Resources/Private/Php/Yaml/Dumper.php";s:4:"be08";s:38:"Resources/Private/Php/Yaml/Escaper.php";s:4:"2c74";s:37:"Resources/Private/Php/Yaml/Inline.php";s:4:"f002";s:34:"Resources/Private/Php/Yaml/LICENSE";s:4:"0b15";s:37:"Resources/Private/Php/Yaml/Parser.php";s:4:"8e1d";s:43:"Resources/Private/Php/Yaml/phpunit.xml.dist";s:4:"f4b5";s:36:"Resources/Private/Php/Yaml/README.md";s:4:"2afd";s:40:"Resources/Private/Php/Yaml/Unescaper.php";s:4:"34cb";s:35:"Resources/Private/Php/Yaml/Yaml.php";s:4:"5517";s:54:"Resources/Private/Php/Yaml/Exception/DumpException.php";s:4:"3e4c";s:59:"Resources/Private/Php/Yaml/Exception/ExceptionInterface.php";s:4:"a7ee";s:55:"Resources/Private/Php/Yaml/Exception/ParseException.php";s:4:"c896";s:46:"Resources/Private/Php/Yaml/Tests/bootstrap.php";s:4:"27a9";s:47:"Resources/Private/Php/Yaml/Tests/DumperTest.php";s:4:"31fe";s:47:"Resources/Private/Php/Yaml/Tests/InlineTest.php";s:4:"bafc";s:47:"Resources/Private/Php/Yaml/Tests/ParserTest.php";s:4:"5da0";s:63:"Resources/Private/Php/Yaml/Tests/Fixtures/escapedCharacters.yml";s:4:"6cd6";s:51:"Resources/Private/Php/Yaml/Tests/Fixtures/index.yml";s:4:"3013";s:56:"Resources/Private/Php/Yaml/Tests/Fixtures/sfComments.yml";s:4:"e988";s:55:"Resources/Private/Php/Yaml/Tests/Fixtures/sfCompact.yml";s:4:"8358";s:56:"Resources/Private/Php/Yaml/Tests/Fixtures/sfMergeKey.yml";s:4:"8091";s:55:"Resources/Private/Php/Yaml/Tests/Fixtures/sfObjects.yml";s:4:"8865";s:54:"Resources/Private/Php/Yaml/Tests/Fixtures/sfQuotes.yml";s:4:"7ab7";s:53:"Resources/Private/Php/Yaml/Tests/Fixtures/sfTests.yml";s:4:"2cbc";s:60:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsAnchorAlias.yml";s:4:"0737";s:59:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsBasicTests.yml";s:4:"d469";s:61:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsBlockMapping.yml";s:4:"4c17";s:66:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsDocumentSeparator.yml";s:4:"ae48";s:59:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsErrorTests.yml";s:4:"de49";s:64:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsFlowCollections.yml";s:4:"326c";s:62:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsFoldedScalars.yml";s:4:"bf21";s:64:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsNullsAndEmpties.yml";s:4:"fda0";s:70:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsSpecificationExamples.yml";s:4:"e69a";s:62:"Resources/Private/Php/Yaml/Tests/Fixtures/YtsTypeTransfers.yml";s:4:"38b9";s:48:"Resources/Private/Templates/Importer/Import.html";s:4:"2676";s:47:"Resources/Private/Templates/Importer/Index.html";s:4:"621b";s:49:"Resources/Private/Templates/Importer/Preview.html";s:4:"2d31";s:33:"Resources/Public/Icons/Import.png";s:4:"cbe9";s:35:"Resources/Public/Icons/Strategy.png";s:4:"c23c";s:47:"Resources/Public/JavaScript/Components/Table.js";s:4:"d756";}',
	'suggests' => array(
	),
);

?>