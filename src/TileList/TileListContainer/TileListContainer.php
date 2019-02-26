<?php

namespace srag\Plugins\SrTile\TileList\TileListContainer;

use srag\Plugins\SrTile\TileList\TileListAbstract;

/**
 * Class TileListContainer
 *
 * @package srag\Plugins\SrTile\TileList\TileListContainer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListContainer extends TileListAbstract {

	/**
	 * @var string
	 */
	protected $html;


	/**
	 * TileListContainer constructor
	 *
	 * @param string $html
	 */
	protected function __construct(string $html) /*: void*/ {
		$this->html = $html;

		parent::__construct();
	}


	/**
	 * @inheritdoc
	 */
	protected function initObjRefIds() /*: void*/ {
		$obj_ref_ids = [];

		preg_match_all('/id\\s*=\\s*"lg_div_([0-9]+)/', $this->html, $obj_ref_ids);

		if (is_array($obj_ref_ids) && count($obj_ref_ids) > 1 && is_array($obj_ref_ids[1]) && count($obj_ref_ids[1]) > 0) {

			$this->obj_ref_ids = array_map(function (string $obj_ref_id): int {
				return intval($obj_ref_id);
			}, $obj_ref_ids[1]);
		}
	}
}
