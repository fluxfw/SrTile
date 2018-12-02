<?php

namespace srag\RemovePluginDataConfirm\SrTile;

use ilException;

/**
 * Class RemovePluginDataConfirmException
 *
 * @package srag\RemovePluginDataConfirm\SrTile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RemovePluginDataConfirmException extends ilException {

	/**
	 * RemovePluginDataConfirmException constructor
	 *
	 * @param string $message
	 * @param int    $code
	 *
	 * @internal
	 */
	public function __construct(/*string*/
		$message, /*int*/
		$code = 0) {
		parent::__construct($message, $code);
	}
}
