<?php

namespace srag\Plugins\SrTile\TileList\TileListStatic;

use srag\Plugins\SrTile\TileList\TileListAbstract;

/**
 * Class TileListStatic
 *
 * @package srag\Plugins\SrTile\TileList\TileListStatic
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListStatic extends TileListAbstract {

	/**
	 * TileListStatic constructor
	 *
	 * @param array $obj_ref_ids
	 */
	protected function __construct(array $obj_ref_ids) /*: void*/ {
		$this->obj_ref_ids = $obj_ref_ids;

		parent::__construct();
	}


	/**
	 * @inheritdoc
	 */
	protected function initObjRefIds() /*: void*/ {

	}
}
