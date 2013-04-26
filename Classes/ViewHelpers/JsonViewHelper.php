<?php
namespace TYPO3\Importer\ViewHelpers;
/**
 * JSON de- and encode
 *
 * @author     Michael Feinbier <mf@hdnet.de>
 * @version    SVN: $Id$
 * @package    Importer
 * @subpackage ViewHelper
 */
class JsonViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return string
	 */
	public function render() {
		return addslashes(json_encode($this->renderChildren()));
	}
}