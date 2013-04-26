<?php
namespace TYPO3\Importr\Service\Targets;
/*
 *   Copyright notice
 *
 *   (c) 2011 Tim Lochmüller <tl@hdnet.de>, HDNET GmbH & Co. KG
 *
 *   All rights reserved
 *
 *   This script is part of the TYPO3 project. The TYPO3 project is
 *   free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   The GNU General Public License can be found at
 *   http://www.gnu.org/copyleft/gpl.html.
 *
 *   This script is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   This copyright notice MUST APPEAR in all copies of the script!
 */
/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class ExtbaseModel extends AbstractTarget implements TargetInterface {
	/**
	 * @var \TYPO3\Importr\Domain\Model\Strategy
	 */
	protected $strategy;

	/**
	 * @return array
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['pid'] = (isset($configuration['pid']) && is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;
		return $configuration;
	}

	/**
	 * @param \TYPO3\Importr\Domain\Model\Strategy $strategy
	 * @return void
	 */
	public function start(\TYPO3\Importr\Domain\Model\Strategy $strategy) {
		$this->epm = \TYPO3\Importr\Utility::createObject('Tx_Extbase_Persistence_Manager');
		$this->strategy = $strategy;
	}

	/**
	 * @param array $entry
	 * @return int|void
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		$this->repository = \TYPO3\Importr\Utility::createObject($configuration['repository']);
		$model = $this->mapModel($this->getModel(), $configuration['mapping'], $entry);
		$this->repository->add($model);
		$this->epm->persistAll();
		if (isset($configuration['language'])) {
			foreach ($configuration['language'] as $languageKey => $mapping) {
				$modelLang = $this->mapModel($this->getModel(), $mapping, $entry);
				$modelLang->setSysLanguageUid($languageKey);
				$modelLang->setL10nParent($model);
				$this->repository->add($modelLang);
			}
		}
		$this->epm->persistAll();

		return TargetInterface::RESULT_INSERT;
	}

	public function end() {
	}

	/**
	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model
	 * @param array                                  $mapping
	 * @param                                        $entry
	 * @return \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	 */
	protected function mapModel($model, $mapping, $entry) {
		if (is_array($mapping)) {
			foreach ($mapping as $key => $value) {
				$model->_setProperty($value, $entry[$key]);
			}
		}
		return $model;
	}

	/**
	 * get a model in the right location
	 *
	 * @return \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	 */
	protected function getModel() {
		$configuration = $this->getConfiguration();
		$model = new $configuration['model'];
		$model->setPid($configuration['pid']);
		return $model;
	}
}
