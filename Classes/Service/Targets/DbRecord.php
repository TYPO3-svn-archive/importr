<?php
namespace TYPO3\Importr\Service\Targets;

/**
 * Description of Tx_Importr_Service_Targets_DbRecord
 *
 * @author timlochmueller
 */
class DbRecord extends AbstractTarget implements TargetInterface {
	/**
	 * @param \TYPO3\Importr\Domain\Model\Strategy $strategy
	 */
	public function start(\TYPO3\Importr\Domain\Model\Strategy $strategy) {

	}

	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['pid'] = (isset($configuration['pid']) && is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;

		return $configuration;
	}

	/**
	 * @param array $entry
	 * @return integer
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		$mapping = $configuration['mapping'];

		$insertFields = array();
		foreach ($mapping as $key => $value) {
			$insertFields[$value] = $entry[$key];
		}

		$insertFields['pid'] = $configuration['pid'];

		$GLOBALS['TYPO3_DB']->exec_INSERTquery($configuration['table'], $insertFields);
		return TargetInterface::RESULT_INSERT;
	}
	/**
	 *
	 */
	public function end() {

	}
}