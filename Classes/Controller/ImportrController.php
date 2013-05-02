<?php
namespace TYPO3\Importr\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Description of ImportrController
 *
 * @author timlochmueller
 */
class ImportrController extends ActionController {

	/**
	 * @var \TYPO3\CMS\Core\Resource\ResourceFactory
	 * @inject
	 */
	protected $resourceFactory;

	/**
	 * @var \TYPO3\Importr\Domain\Repository\StrategyRepository
	 * @inject
	 */
	protected $strategyRepository;

	/**
	 * @var \TYPO3\Importr\Domain\Repository\ImportRepository
	 * @inject
	 */
	protected $importRepository;

	/**
	 * @var \TYPO3\Importr\Service\Manager
	 * @inject
	 */
	protected $importManager;

	/**
	 * @return void
	 */
	public function indexAction() {
		$combinedIdentifier = GeneralUtility::_GP('id');
		if (isset($combinedIdentifier)) {
			$folder = $this->resourceFactory->getFolderObjectFromCombinedIdentifier($combinedIdentifier);
			$files = array();
			foreach ($folder->getFiles() as $file) {
				$files[$file
					->getStorage()
					->getUid() . ':' . $file->getIdentifier()] = $file->getName();
			}
			$this->view->assign('folder', $files);
		}
		$this->view->assign('imports', $this->importRepository->findUserQueue());
	}

	/**
	 * @param string $identifier
	 */
	public function importAction($identifier) {
		$file = $this->resourceFactory->getObjectFromCombinedIdentifier($identifier);
		$this->view->assign('file', $file);
		$this->view->assign('strategies', $this->strategyRepository->findAllUser());
	}

	/**
	 *
	 * @param string                                $identifier
	 * @param \TYPO3\Importr\Domain\Model\Strategy $strategy
	 *
	 * @return void
	 */
	public function previewAction($identifier, \TYPO3\Importr\Domain\Model\Strategy $strategy) {
		$file = $this->resourceFactory->getObjectFromCombinedIdentifier($identifier);
		$this->view->assign('filepath', $file->getPublicUrl());
		$this->view->assign('strategy', $strategy);

		$previewData = $this->importManager->getPreview($strategy, $file->getPublicUrl());
		$this->view->assign('preview', $previewData);
	}

	/**
	 *
	 * @param string                                $filepath
	 * @param \TYPO3\Importr\Domain\Model\Strategy $strategy
	 *
	 * @return void
	 */
	public function createAction($filepath, \TYPO3\Importr\Domain\Model\Strategy $strategy) {
		$this->importManager->addToQueue($filepath, $strategy);
		$this->flashMessageContainer->add('The Import file ' . $filepath . ' width the strategy ' . $strategy->getTitle() . ' was successfully added to the queue', 'Import is in Queue');
		$this->redirect('index');
	}

}
