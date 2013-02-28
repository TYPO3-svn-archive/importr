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
 * Description of Yaml
 *
 * @author timlochmueller
 */
class Tx_Importer_Service_Yaml {
	
	/**
	 * @param $input string
	 * @return array
	 */
	public static function parse($input) {
		
		/**
		 * Maybe an custom autoloader would be cool here.
		 */
		$yamlComponentPath = t3lib_extMgm::extPath('importer', 'Resources/Private/Php/Yaml/');
		require_once $yamlComponentPath.'Yaml.php';
		require_once $yamlComponentPath.'Parser.php';
		require_once $yamlComponentPath.'Inline.php';
		require_once $yamlComponentPath.'Dumper.php';
		require_once $yamlComponentPath.'Escaper.php';
		require_once $yamlComponentPath.'Unescaper.php';
		require_once $yamlComponentPath.'Exception/ExceptionInterface.php';
		require_once $yamlComponentPath.'Exception/ParseException.php';
		require_once $yamlComponentPath.'Exception/DumpException.php';
		
		try {
			$array = Symfony\Component\Yaml\Yaml::parse($input);
			/**
			 * The parser can return integer or string 
			 * if an number or a string is passed to it.
			 * We always need an configuration array, so
			 * we drop any other datatype here.
			 */
			if(!is_array($array)) {
				$array = array();
			}
		} catch (Symfony\Component\Yaml\Exception\ParseException $e) {
			/**
			 * @todo maybe log the error
			 */
			$array = array();
		}
		return $array;
	}
}