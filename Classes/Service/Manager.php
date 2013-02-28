<?php
/**
 * Service Manager
 *
 * @package    Extension\importer
 * @subpackage Service
 */
/**
 * Service Manager
 *
 * @package     Extension\importer
 * @subpackage  Service
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Tim Lochmüller <tim.lochmueller@hdnet.de>
 * @version     $Id: Manager.php 407 2013-02-08 13:27:30Z tspiekerkoetter $
 */
class Tx_Importer_Service_Manager {

	/**
	 * @var Tx_Importer_Domain_Repository_ImportRepository
	 */
	protected $importRepository;

	/**
	 * @var Tx_Importer_Domain_Repository_StrategyRepository
	 */
	protected $strategyRepository;

	/**
	 * @var Tx_Extbase_SignalSlot_Dispatcher
	 */
	protected $signalSlotDispatcher;

	/**
	 * @var Tx_Extbase_Persistence_Manager
	 */
	protected $persistenceManager;

	/**
	 * Update Interval
	 *
	 * @var integer
	 */
	protected $updateInterval = 1;

	/**
	 * @param Tx_Importer_Domain_Repository_ImportRepository $importRepository
	 */
	protected function injectImportRepository(Tx_Importer_Domain_Repository_ImportRepository $importRepository) {
		$this->importRepository = $importRepository;
	}

	/**
	 * @param Tx_Importer_Domain_Repository_StrategyRepository $strategyRepository
	 */
	protected function injectStrategyRepository(Tx_Importer_Domain_Repository_StrategyRepository $strategyRepository) {
		$this->strategyRepository = $strategyRepository;
	}

	/**
	 * @param Tx_Extbase_SignalSlot_Dispatcher $signalSlotDispatcher
	 */
	public function injectSignalSlotDispatcher(Tx_Extbase_SignalSlot_Dispatcher $signalSlotDispatcher) {
		$this->signalSlotDispatcher = $signalSlotDispatcher;
	}

	/**
	 * @param Tx_Extbase_Persistence_Manager $persistenceManager
	 */
	public function injectPersistenceManager(Tx_Extbase_Persistence_Manager $persistenceManager) {
		$this->persistenceManager = $persistenceManager;
	}

	/**
	 * pseudo dependency injection
	 */
	public function __construct() {
		$this->injectImportRepository(Tx_Importer_Utility::createObject('Tx_Importer_Domain_Repository_ImportRepository'));
		$this->injectStrategyRepository(Tx_Importer_Utility::createObject('Tx_Importer_Domain_Repository_StrategyRepository'));
		$this->injectSignalSlotDispatcher(Tx_Importer_Utility::createObject('Tx_Extbase_SignalSlot_Dispatcher'));
		$this->injectPersistenceManager(Tx_Importer_Utility::createObject('Tx_Extbase_Persistence_Manager'));
	}

	/**
	 * @param string                            $filepath
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @param array                             $configuration
	 */
	public function addToQueue($filepath, Tx_Importer_Domain_Model_Strategy $strategy, $configuration = array()) {
		$import = new Tx_Importer_Domain_Model_Import();
		$start = 'now';
		if (isset($configuration['start'])) {
			$start = $configuration['start'];
		}
		try {
			$startTime = new DateTime($start);
		} catch (Exception $e) {
			$startTime = new DateTime();
		}
		$import->setStarttime($startTime);
		$import->setFilepath($filepath);
		$import->setStrategy($strategy);
		$this->importRepository->add($import);
		$this->persistenceManager->persistAll();
	}

	/**
	 * run the Importer
	 */
	public function runImports() {
		try {
			$imports = $this->importRepository->findWorkQueue();
			foreach ($imports as $import) {
				$this->runImport($import);
			}
		} catch (Tx_Importer_Exception_ReinitializeException $exc) {
			$this->runImports();
		}
	}

	/**
	 * Get the preview
	 *
	 * @param string                            $filepath
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 *
	 * @return array
	 */
	public function getPreview(Tx_Importer_Domain_Model_Strategy $strategy, $filepath) {
		$data = array();
		$resources = $this->initializeResources($strategy, $filepath);
		foreach ($resources as $resource) {
			/** @var Tx_Importer_Service_Resources_ResourceInterface $resource */
			// Resourcen Object anhand der Datei auswählen
			if (preg_match($resource->getFilepathExpression(), $filepath)) {
				// Resource "benutzen"
				$resource->parseResource();
				// Durchlauf starten
				for ($pointer = 0; $pointer <= 20; $pointer++) {
					if ($resource->getEntry($pointer)) {
						$data[] = $resource->getEntry($pointer);
					}
				}
				break;
			}
		}
		return $data;
	}

	/**
	 * Magic Runner
	 *
	 * @param Tx_Importer_Domain_Model_Import $import
	 */
	protected function runImport(Tx_Importer_Domain_Model_Import $import) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'preImport', array($this, $import));
		$resources = $this->initializeResourcesByImport($import);
		$targets = $this->initializeTargets($import);
		$strategyConfiguration = $import
			->getStrategy()
			->getConfiguration(TRUE);

		foreach ($resources as $resource) {
			/** @var Tx_Importer_Service_Resources_ResourceInterface $resource */
			// Resourcen Object anhand der Datei auswählen
			if (preg_match($resource->getFilepathExpression(), $import->getFilepath())) {
				if (is_array($strategyConfiguration['before'])) {
					$this->parseConfiguration($strategyConfiguration['before']);
				}
				// Resource "benutzen"
				$resource->parseResource();
				// Basis Import Aktualsieren (DB)
				$import->setAmount($resource->getAmount());
				$import->getStarttime(new DateTime('now'));
				$this->updateImport($import);
				// Durchlauf starten
				for ($pointer = $import->getPointer(); $pointer < $import->getAmount(); $pointer++) {
					if (is_array($strategyConfiguration['each'])) {
						$this->parseConfiguration($strategyConfiguration['each']);
					}
					$entry = $resource->getEntry($pointer);
					foreach ($targets as $target) {
						$this->processTarget($target, $entry, $import, $pointer);
					}
					if (($pointer + 1) % $this->updateInterval == 0) {
						$this->updateImport($import, $pointer);
					}
				}
				$import->setEndtime(new DateTime('now'));
				$this->updateImport($import, $pointer);
				if (is_array($strategyConfiguration['after'])) {
					$this->parseConfiguration($strategyConfiguration['after']);
				}
				break;
			}
		}
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'pastImport', array($this, $import));
	}

	/**
	 * @param Tx_Importer_Service_Targets_TargetInterface $target
	 * @param mixed                                       $entry
	 * @param Tx_Importer_Domain_Model_Import             $import
	 * @param int                                         $pointer
	 *
	 * @throws Exception
	 */
	protected function processTarget(Tx_Importer_Service_Targets_TargetInterface $target, $entry, Tx_Importer_Domain_Model_Import $import, $pointer) {
		try {
			$result = $target->processEntry($entry);
			$import->increaseCount($result);
		} catch (Exception $e) {
			$import->increaseCount(Tx_Importer_Service_Targets_TargetInterface::RESULT_ERROR);
			$this->updateImport($import, $pointer + 1);
			throw $e;
		}
	}

	/**
	 * Parse the configuration
	 *
	 * @param array $configuration
	 *
	 * @throws Tx_Importer_Exception_ReinitializeException
	 */
	protected function parseConfiguration(array $configuration) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'preParseConfiguration', array($this, $configuration));
		if (isset($configuration['updateInterval'])) {
			$this->updateInterval = (int)$configuration['updateInterval'];
		}
		if (isset($configuration['createImport']) && is_array($configuration['createImport'])) {
			foreach ($configuration['createImport'] as $create) {
				$strategy = $this->strategyRepository->findByUid((int)$create['importId']);
				if ($strategy instanceof Tx_Importer_Domain_Model_Strategy) {
					$filepath = isset($create['filepath']) ? $create['filepath'] : '';
					$this->addToQueue($filepath, $strategy, $create);
				}
			}
		}
		if (isset($configuration['reinitializeScheduler'])) {
			throw new Tx_Importer_Exception_ReinitializeException();
		}
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'pastParseConfiguration', array($this, $configuration));
	}

	/**
	 * Parse the configuration
	 *
	 * @param array $configuration
	 *
	 * @deprecated use parseConfiguration instead
	 */
	protected function parseConfguration(array $configuration) {
		$this->parseConfiguration($configuration);
	}

	/**
	 * @param Tx_Importer_Domain_Model_Import $import
	 *
	 * @return array
	 */
	protected function initializeResourcesByImport(Tx_Importer_Domain_Model_Import $import) {
		return $this->initializeResources($import->getStrategy(), $import->getFilepath());
	}

	/**
	 * @param Tx_Importer_Domain_Model_Strategy $strategy
	 * @param string                            $filepath
	 *
	 * @return array
	 */
	protected function initializeResources(Tx_Importer_Domain_Model_Strategy $strategy, $filepath) {
		$resources = array();
		$resourceConfiguration = $strategy->getResources(TRUE);
		foreach ($resourceConfiguration as $resource => $configuration) {
			$object = Tx_Importer_Utility::createObject($resource);
			$object->start($strategy, $filepath);
			$object->setConfiguration($configuration);
			$resources[$resource] = $object;
		}
		return $resources;
	}

	/**
	 * @param Tx_Importer_Domain_Model_Import $import
	 *
	 * @return array
	 */
	protected function initializeTargets(Tx_Importer_Domain_Model_Import $import) {
		$targets = array();
		$targetConfiguration = $import
			->getStrategy()
			->getTargets(TRUE);
		foreach ($targetConfiguration as $target => $configuration) {
			$object = Tx_Importer_Utility::createObject($target);
			$object->setConfiguration($configuration);
			$object->getConfiguration();
			$object->start($import->getStrategy());
			$targets[$target] = $object;
		}
		return $targets;
	}

	/**
	 * @param Tx_Importer_Domain_Model_Import $import
	 * @param bool|int                        $pointer
	 */
	protected function updateImport(Tx_Importer_Domain_Model_Import $import, $pointer = FALSE) {
		if ($pointer) {
			$import->setPointer($pointer);
		}
		$this->importRepository->update($import);
		$this->persistenceManager->persistAll();
	}
}
