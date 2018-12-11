<?php

namespace srag\Plugins\SrTile\Tile;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Tiles
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Tiles {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var Tile[]
	 */
	protected static $instances_by_ref_id = [];


	/**
	 * Tiles constructor
	 */
	private function __construct() {

	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return Tile|null
	 */
	public function getInstanceForObjRefId(int $obj_ref_id) /*:?Tile*/ {
		if (self::$instances_by_ref_id[$obj_ref_id] === NULL) {
			if (self::$instances_by_ref_id[$obj_ref_id] = Tile::where([ 'obj_ref_id' => $obj_ref_id ])->first()) {
				return self::$instances_by_ref_id[$obj_ref_id];
			};

			return NULL;
		}

		return self::$instances_by_ref_id[$obj_ref_id];
	}
}
