<?php

/**
 * JSON de- and encode
 *
 * @author     Michael Feinbier <mf@hdnet.de>
 * @version    SVN: $Id$
 * @package    Importer
 * @subpackage ViewHelper
 */
class Tx_Importer_ViewHelpers_JsonViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @return string
	 */
	public function render() {
		return addslashes(json_encode($this->renderChildren()));
	}
}