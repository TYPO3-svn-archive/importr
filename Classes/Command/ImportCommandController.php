<?php
namespace TYPO3\Importer\Command;

	/**
	 * Import CommandController for initializing the Tx_Importer_Service_Manager
	 *
	 * @package    Extension\importer
	 * @subpackage Command
	 */

/**
 * Import CommandController for initializing the Tx_Importer_Service_Manager
 *
 * @package     Extension\importer
 * @subpackage  Command
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Tim Lochmüller <tim.lochmueller@hdnet.de>
 * @author      Tim Spiekerkötter <tim.spiekerkoetter@hdnet.de>
 * @version     $Id:$
 */
class ImportCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Cli\CommandManager
	 * @inject
	 */
	protected $commandManager;

	/**
	 * @var array
	 */
	protected $commandsByExtensionsAndControllers = array();

	/**
	 * initializes the import service manager
	 *
	 * @param string $mail Set an email address for error reporting
	 *
	 * @return boolean
	 */
	public function initializeServiceManagerCommand($mail = NULL) {
		/**
		 * @var \TYPO3\CMS\Core\Messaging\FlashMessage $message
		 */
		$message = $this->objectManager->create('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', '', 'Initializing ServiceManager', \TYPO3\CMS\Core\Messaging\FlashMessage::INFO);
		\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
		/**
		 * @var \TYPO3\Importer\Service\Manager $manager
		 */
		$manager = $this->objectManager->get('TYPO3\\Importer\\Service\\Manager');
		try {
			// let the manager run the imports now
			$manager->runImports();
		} catch (\Exception $e) {
			/**
			 * @var \TYPO3\CMS\Core\Messaging\FlashMessage $message
			 */
			$message = $this->objectManager->create('TYPO3\\CMS\\Core\\Messaging\\FlashMessage', '', 'An Error occured: ' . $e->getCode() . ': ' . $e->getMessage(), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			\TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($message);
			// if mail is configured send an email
			if ($mail !== NULL && \TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($mail)) {
				// @TODO: send mail
			}
			return FALSE;
		}
		return TRUE;
	}
}
