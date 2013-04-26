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
namespace TYPO3\Importer\Service\Targets;
/**
 * Description of TargetInterface
 *
 * @author timlochmueller
 */
interface TargetInterface {

	const RESULT_INSERT = 1;
	const RESULT_UPDATE = 2;
	const RESULT_IGNORED = 3;
	const RESULT_UNSURE = 4;
	const RESULT_ERROR = 5;

	/**
	 * @param $strategy \TYPO3\Importer\Domain\Model\Strategy
	 */
	public function start(\TYPO3\Importer\Domain\Model\Strategy $strategy);

	/**
	 *
	 * @param $entry array
	 * @return integer
	 */
	public function processEntry(array $entry);

	/**
	 *
	 */
	public function end();
}