<?php

namespace srag\Plugins\SrTile\TileList;

use srag\Plugins\srTile\Tile\Tile;

/**
 * Interface Tile
 *
 * @package srag\Plugins\SrTile\TileList
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
interface TileListInterface {

	/**
	 * @param mixed $param
	 *
	 * @return TileListInterface
	 */
	public static function getInstance($param): TileListInterface;


	/**
	 * @param Tile $tile
	 */
	public function addTile(Tile $tile)/*: void*/
	;


	/**
	 * @param int $tile_id
	 */
	public function removeTile(int $tile_id)/*: void*/
	;


	/**
	 * @return Tile[]
	 */
	public function getTiles(): array;
}
