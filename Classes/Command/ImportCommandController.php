<?php
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
class Tx_Importer_Command_ImportCommandController extends Tx_Extbase_MVC_Controller_CommandController {

	/**
	 * @var Tx_Extbase_MVC_CLI_CommandManager
	 */
	protected $commandManager;

	/**
	 * @var array
	 */
	protected $commandsByExtensionsAndControllers = array();

	/**
	 * @param Tx_Extbase_MVC_CLI_CommandManager $commandManager
	 * @return void
	 */
	public function injectCommandManager(Tx_Extbase_MVC_CLI_CommandManager $commandManager) {
		$this->commandManager = $commandManager;
	}

	/**
	 * initializes the import service manager
	 *
	 * @param string $mail Set an email address for error reporting
	 * @return boolean
	 */
	public function initializeServiceManagerCommand($mail = NULL) {
		/**
		 * @var t3lib_FlashMessage $message
		 */
		$message = $this->objectManager->create('t3lib_FlashMessage', '', 'Initializing ServiceManager', t3lib_FlashMessage::INFO);
		t3lib_FlashMessageQueue::addMessage($message);
		/**
		 * @var Tx_Importer_Service_Manager $manager
		 */
		$manager = $this->objectManager->get('Tx_Importer_Service_Manager');
		try {
			// let the manager run the imports now
			$manager->runImports();
		} catch (Exception $e) {
			/**
			 * @var t3lib_FlashMessage $message
			 */
			$message = $this->objectManager->create('t3lib_FlashMessage', '', 'An Error occured: ' . $e->getCode() . ': ' . $e->getMessage(), t3lib_FlashMessage::ERROR);
			t3lib_FlashMessageQueue::addMessage($message);
			// if mail is configured send an email
			if ($mail !== NULL && t3lib_div::validEmail($mail)) {
				// @TODO: send mail
			}
			return FALSE;
		}
		return TRUE;
	}
}
