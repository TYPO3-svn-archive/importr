<?php

namespace TYPO3\Importr\Service\Resources;
/**
 * Description of ResourceInterface
 *
 * @author timlochmueller
 */
interface ResourceInterface {

	/**
	 * @param $strategy \TYPO3\Importr\Domain\Model\Strategy
	 * @param $filepath array
	 */
	public function start(\TYPO3\Importr\Domain\Model\Strategy $strategy, $filepath);

	/**
	 * @return string
	 */
	public function getFilepathExpression();

	/**
	 *
	 */
	public function parseResource();

	/**
	 * @return integer
	 */
	public function getAmount();

	/**
	 * @param integer $pointer
	 */
	public function getEntry($pointer);

	/**
	 *
	 */
	public function end();
}