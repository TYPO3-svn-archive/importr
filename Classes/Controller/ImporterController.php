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
 * Description of ImporterController
 *
 * @author timlochmueller
 */
class Tx_Importer_Controller_ImporterController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Importer_Domain_Repository_StrategyRepository
	 */
	protected $strategyRepository;

	/**
	 * @var Tx_Importer_Domain_Repository_ImportRepository
	 */
	protected $importRepository;

	/**
	 * @var Tx_Importer_Service_Manager
	 */
	protected $importManager;

	/**
	 * @param Tx_Importer_Domain_Repository_StrategyRepository $strategyRepository
	 */
	public function injectStrategyRepository(Tx_Importer_Domain_Repository_StrategyRepository $strategyRepository) {
		$this->strategyRepository = $strategyRepository;
	}

	/**
	 * @param Tx_Importer_Domain_Repository_ImportRepository $importRepository
	 */
	public function injectImportRepository(Tx_Importer_Domain_Repository_ImportRepository $importRepository) {
		$this->importRepository = $importRepository;
	}

	/**
	 * @param Tx_Importer_Service_Manager $importManager
	 */
	public function injectImportManager(Tx_Importer_Service_Manager $importManager) {
		$this->importManager = $importManager;
	}

	/**
	 * @todo Check security of id!!!
	 * @return void
	 */
	public function indexAction() {

		$dir = FALSE;
		if (isset($_GET['id']) && is_dir($_GET['id'])) {
			$dir = $_GET['id'];
		}

		$this->view->assign('dir', $dir);
		if ($dir) {
			$baseDir = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/';
			$glob = glob($dir . '*');
			$files = array();
			foreach ($glob as $value) {
				if (is_file($value))
					$files[str_replace($baseDir, '', $value)] = pathinfo($value, PATHINFO_BASENAME);
			}
			$this->view->assign('files', $files);
		}

		$this->view->assign('imports', $this->importRepository->findUserQueue());
	}

	/**
	 *
	 * @param string $filepath
	 * @return void
	 */
	public function importAction($filepath) {
		$this->view->assign('filepath', $filepath);
		$this->view->assign('strategies', $this->strategyRepository->findAllUser());
	}

	/**
	 *
	 * @param string                            $filepath
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @return void
	 */
	public function previewAction($filepath, Tx_Importer_Domain_Model_Strategy $strategy) {
		$this->view->assign('filepath', $filepath);
		$this->view->assign('strategy', $strategy);

		$previewData = $this->importManager->getPreview($strategy, $filepath);
		$this->view->assign('preview', $previewData);
	}

	/**
	 *
	 * @param string                            $filepath
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @return void
	 */
	public function createAction($filepath, Tx_Importer_Domain_Model_Strategy $strategy) {
		$this->importManager->addToQueue($filepath, $strategy);
		$this->flashMessageContainer->add('The Import file ' . $filepath . ' width the strategy ' . $strategy->getTitle() . ' was successfully added to the queue', 'Import is in Queue');
		$this->redirect('index');
	}

}