<?php
/**
 * Service Manager
 *
 * @package    Extension\importer
 * @subpackage Service
 */
namespace TYPO3\Importer\Service;
/**
 * Service Manager
 *
 * @package     Extension\importer
 * @subpackage  Service
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Tim Lochmüller <tim.lochmueller@hdnet.de>
 * @version     $Id: Manager.php 490 2013-03-05 09:49:47Z tspiekerkoetter $
 */
class Manager {

	/**
	 * @var \TYPO3\Importer\Domain\Repository\ImportRepository
	 * @inject
	 */
	protected $importRepository;

	/**
	 * @var \TYPO3\Importer\Domain\Repository\StrategyRepository
	 * @inject
	 */
	protected $strategyRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 * @inject
	 */
	protected $signalSlotDispatcher;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Update Interval
	 *
	 * @var integer
	 */
	protected $updateInterval = 1;

	/**
	 * @param string                            $filepath
	 * @param \TYPO3\Importer\Domain\Model\Strategy $strategy
	 * @param array                             $configuration
	 */
	public function addToQueue($filepath, \TYPO3\Importer\Domain\Model\Strategy $strategy, $configuration = array()) {
		/** @var $import \TYPO3\Importer\Domain\Model\Import */
		$import = $this->objectManager->create('TYPO3\Importer\Domain\Model\Import');
		$start = 'now';
		if (isset($configuration['start'])) {
			$start = $configuration['start'];
		}
		try {
			$startTime = new \DateTime($start);
		} catch (\Exception $e) {
			$startTime = new \DateTime();
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
		} catch (\TYPO3\Importer\Exception\ReinitializeException $exc) {
			$this->runImports();
		}
	}

	/**
	 * Get the preview
	 *
	 * @param string                            $filepath
	 * @param \TYPO3\Importer\Domain\Model\Strategy $strategy
	 *
	 * @return array
	 */
	public function getPreview(\TYPO3\Importer\Domain\Model\Strategy $strategy, $filepath) {
		$data = array();
		$resources = $this->initializeResources($strategy, $filepath);
		foreach ($resources as $resource) {
			/** @var \TYPO3\Importer\Service\Resources\ResourceInterface $resource */
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
	 * @param \TYPO3\Importer\Domain\Model\Import $import
	 */
	protected function runImport(\TYPO3\Importer\Domain\Model\Import $import) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'preImport', array($this, $import));
		$resources = $this->initializeResourcesByImport($import);
		$targets = $this->initializeTargets($import);
		$strategyConfiguration = $import
			->getStrategy()
			->getConfiguration(TRUE);

		foreach ($resources as $resource) {
			/** @var \TYPO3\Importer\Service\Resources\ResourceInterface $resource */
			// Resourcen Object anhand der Datei auswählen
			if (preg_match($resource->getFilepathExpression(), $import->getFilepath())) {
				if (is_array($strategyConfiguration['before'])) {
					$this->parseConfiguration($strategyConfiguration['before']);
				}
				// Resource "benutzen"
				$resource->parseResource();
				// Basis Import Aktualsieren (DB)
				$import->setAmount($resource->getAmount());
				$import->getStarttime(new \DateTime('now'));
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
				$import->setEndtime(new \DateTime('now'));
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
	 * @param \TYPO3\Importer\Service\Targets\TargetInterface $target
	 * @param mixed                                       $entry
	 * @param \TYPO3\Importer\Domain\Model\Import             $import
	 * @param int                                         $pointer
	 *
	 * @throws \Exception
	 */
	protected function processTarget(\TYPO3\Importer\Service\Targets\TargetInterface $target, $entry, \TYPO3\Importer\Domain\Model\Import $import, $pointer) {
		try {
			$result = $target->processEntry($entry);
			$import->increaseCount($result);
		} catch (\Exception $e) {
			$import->increaseCount(\TYPO3\Importer\Service\Targets\TargetInterface::RESULT_ERROR);
			$this->updateImport($import, $pointer + 1);
			throw $e;
		}
	}

	/**
	 * Parse the configuration
	 *
	 * @param array $configuration
	 *
	 * @throws \TYPO3\Importer\Exception\ReinitializeException
	 */
	protected function parseConfiguration(array $configuration) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, 'preParseConfiguration', array($this, $configuration));
		if (isset($configuration['updateInterval'])) {
			$this->updateInterval = (int)$configuration['updateInterval'];
		}
		if (isset($configuration['createImport']) && is_array($configuration['createImport'])) {
			foreach ($configuration['createImport'] as $create) {
				$strategy = $this->strategyRepository->findByUid((int)$create['importId']);
				if ($strategy instanceof \TYPO3\Importer\Domain\Model\Strategy) {
					$filepath = isset($create['filepath']) ? $create['filepath'] : '';
					$this->addToQueue($filepath, $strategy, $create);
				}
			}
		}
		if (isset($configuration['reinitializeScheduler'])) {
			throw new \TYPO3\Importer\Exception\ReinitializeException();
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
	 * @param \TYPO3\Importer\Domain\Model\Import $import
	 *
	 * @return array
	 */
	protected function initializeResourcesByImport(\TYPO3\Importer\Domain\Model\Import $import) {
		return $this->initializeResources($import->getStrategy(), $import->getFilepath());
	}

	/**
	 * @param \TYPO3\Importer\Domain\Model\Strategy $strategy
	 * @param string                            $filepath
	 *
	 * @return array
	 */
	protected function initializeResources(\TYPO3\Importer\Domain\Model\Strategy $strategy, $filepath) {
		$resources = array();
		$resourceConfiguration = $strategy->getResources(TRUE);
		foreach ($resourceConfiguration as $resource => $configuration) {
			$object = $this->objectManager->create($resource);
			$object->start($strategy, $filepath);
			$object->setConfiguration($configuration);
			$resources[$resource] = $object;
		}
		return $resources;
	}

	/**
	 * @param \TYPO3\Importer\Domain\Model\Import $import
	 *
	 * @return array
	 */
	protected function initializeTargets(\TYPO3\Importer\Domain\Model\Import $import) {
		$targets = array();
		$targetConfiguration = $import
			->getStrategy()
			->getTargets(TRUE);
		foreach ($targetConfiguration as $target => $configuration) {
			$object = $this->objectManager->create($target);
			$object->setConfiguration($configuration);
			$object->getConfiguration();
			$object->start($import->getStrategy());
			$targets[$target] = $object;
		}
		return $targets;
	}

	/**
	 * @param \TYPO3\Importer\Domain\Model\Import $import
	 * @param bool|int                        $pointer
	 */
	protected function updateImport(\TYPO3\Importer\Domain\Model\Import $import, $pointer = FALSE) {
		if ($pointer) {
			$import->setPointer($pointer);
		}
		$this->importRepository->update($import);
		$this->persistenceManager->persistAll();
	}
}
