<?php

namespace srag\Plugins\SrTile\TileList;

use srag\Plugins\SrTile\Tile\Tile;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
Abstract class TileListAbstract implements TileListInterface {

	use DICTrait;
	use SrTileTrait;
	/**
	 * @var tile[]
	 */
	protected $tiles = array();


	/**
	 * @param Tile $tile
	 */
	public function addTile(Tile $tile) {
		$this->tiles[$tile->getTileId()] = $tile;
	}


	/**
	 * @param int $tile_id
	 */
	public function removeTile(int $tile_id) {
		if (isset($this->tiles[$tile_id])) {
			unset($this->tiles[$tile_id]);
		}
	}


	/**
	 * @return Tile[]
	 */
	public function getTiles(): array {
		return $this->tiles;
	}
}