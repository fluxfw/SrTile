<?php

namespace srag\Plugins\SrTile\Tile;

use ActiveRecord;
use arConnector;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use ilUtil;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TileList {

	use DICTrait;
	use SrTileTrait;
	/**
	 * @var int
	 */
	protected $container_obj_ref_id;
	protected $tiles;


	/**
	 * TileList constructor
	 *
	 * @param int $container_obj_ref_id
	 */
	public function __construct(int $container_obj_ref_id) {
		$this->container_obj_ref_id = $container_obj_ref_id;

		$this->read();
	}


	/**
	 *
	 */
	public function read() {
		$children = self::dic()->tree()->getChilds($this->container_obj_ref_id);

		$tile_list = array();

		foreach ($children as $child) {

			if($child['child'] > 0) {

				$tile = Tile::getInstanceForObjRefId($child['child']);
				$tile_list[] = $tile;
			}

		}

		$this->setTiles($tile_list);
	}


	/**
	 * @return Tile[]
	 */
	public function getTiles():array {
		return $this->tiles;
	}


	/**
	 * @param Tile[]
	 */
	public function setTiles($tiles) {
		$this->tiles = $tiles;
	}


}