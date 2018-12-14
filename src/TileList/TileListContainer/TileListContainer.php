<?php

namespace srag\Plugins\SrTile\TileList\TileListContainer;

use srag\Plugins\SrTile\TileList\TileListAbstract;

;

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
	 * @inheritdoc
	 */
	public function read(array $items = []) /*:void*/ {
		$items = self::dic()->tree()->getChilds($this->getBaseId());

		parent::read($items);
	}
}
