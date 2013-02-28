<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImportLogic
 *
 * @author Tim Spiekerkötter <tim.spiekerkoetter@hdnet.de>
 */
class Tx_Importer_Scheduler_ImportLogic extends Tx_Extbase_Core_Bootstrap {

	/**
	 * @var Tx_Importer_Domain_Repository_ImportRepository 
	 */
	protected $importRepository;

	/**
	 * @param Tx_Importer_Domain_Repository_ImportRepository $importRepository
	 */
	protected function injectImportRepository(Tx_Importer_Domain_Repository_ImportRepository $importRepository) {
		#if($importRepository === NULL) {
		#	$this->importRepository = $this->objectManager->get('Tx_Importer_Domain_Repository_ImportRepository');
		#}else{
			$this->importRepository = $importRepository;
		#}
	}

	/**
	 * 
	 * @param Tx_Importer_Scheduler_Import $pObj
	 * @return boolean
	 */
	public function execute(&$pObj) {

		t3lib_FlashMessageQueue::addMessage(
				t3lib_div::makeInstance('t3lib_FlashMessage', '', 'TEST', t3lib_FlashMessage::INFO)
		);

		$this->importKey = time();
		$this->pObj = $pObj;
		$this->setupFramework();
		
		#$this->injectImportRepository();

		$this->import();
		return TRUE;
	}

	/**
	 * 
	 */
	protected function setupFramework() {
		$configuration = array(
			'extensionName' => 'importer',
			'pluginName' => 'tx_importer_task',
			'settings' => '< plugin.importer.settings',
			'persistence' => '< plugin.importer.persistence',
			'view' => '< plugin.importer.view',
			'persistence.' => array(
				'storagePid' => $this->pObj->getPid()
			),
			'_LOCAL_LANG' => '< plugin.importer._LOCAL_LANG'
		);
		$this->initialize($configuration);
	}

	/**
	 * @todo not select all imports
	 */
	protected function import() {
		#$imports = $this->importRepository->findAll();
		
		$manager = $this->objectManager->get('Tx_Importer_Service_Manager');
		$manager->runImport();
	}

}

?>
