<?php

namespace srag\Plugins\SrTile\TileList;

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileListAbstract
 *
 * @package srag\Plugins\SrTile\TileList
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
abstract class TileListAbstract implements TileListInterface {

	use DICTrait;
	use SrTileTrait;
	/**
	 * @var tile[]
	 */
	protected $tiles = [];


	/**
	 * TileListAbstract constructor
	 */
	protected function __construct() {
		$this->read();
	}


	/**
	 * @inheritdoc
	 */
	public function addTile(Tile $tile)/*:void*/ {
		$this->tiles[$tile->getTileId()] = $tile;
	}


	/**
	 * @inheritdoc
	 */
	public function removeTile(int $tile_id)/*:void*/ {
		if (isset($this->tiles[$tile_id])) {
			unset($this->tiles[$tile_id]);
		}
	}


	/**
	 * @inheritdoc
	 */
	public function getTiles(): array {
		return $this->tiles;
	}


	/**
	 * @inheritdoc
	 */
	public function read(array $items = []) /*:void*/ {
		foreach ($items as $item) {
			if (self::tiles()->isObject($item['child'])) {
				$tile = self::tiles()->getInstanceForObjRefId($item['child']);

				if ($tile->isTileEnabled()) {
					$this->addTile($tile);
				}
			}
		}
	}
}
