<?php

namespace srag\Plugins\SrTile\Tile;

use ilObjectFactory;
use ilObjOrgUnit;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use Throwable;

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
	const GET_PARAM_REF_ID = "ref_id";
	const GET_PARAM_TARGET = "target";
	/**
	 * @var self
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @var Tile[]
	 *
	 * @deprecated
	 */
	protected static $instances_by_ref_id = [];
	/**
	 * @var Tile[]
	 *
	 * @deprecated
	 */
	protected static $parent_tile_cache = [];
	/**
	 * @var bool[]
	 *
	 * @deprecated
	 */
	protected static $is_object_cache = [];


	/**
	 * Tiles constructor
	 */
	private function __construct() {

	}


	/**
	 * @return int|null
	 *
	 * @deprecated
	 */
	public function filterRefId()/*: ?int*/ {
		$obj_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

		if ($obj_ref_id === null) {
			$param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

			$obj_ref_id = explode("_", $param_target)[1];
		}

		$obj_ref_id = intval($obj_ref_id);

		if ($obj_ref_id > 0) {
			return $obj_ref_id;
		} else {
			return null;
		}
	}


	/**
	 * @param int|null $obj_ref_id
	 *
	 * @return Tile
	 *
	 * @deprecated
	 */
	public function getInstanceForObjRefId(int $obj_ref_id = null): Tile {
		if (!isset(self::$instances_by_ref_id[$obj_ref_id])) {
			$tile = Tile::where([ 'obj_ref_id' => $obj_ref_id ])->first();

			if ($tile === null) {
				$tile = new Tile();

				if ($obj_ref_id !== null) {
					$tile->setObjRefId($obj_ref_id);

					$tile->store(); // Ensure tile id

					self::templates()->applyToTile($tile);
				}
			}

			self::$instances_by_ref_id[$obj_ref_id] = $tile;
		}

		return self::$instances_by_ref_id[$obj_ref_id];
	}


	/**
	 * @param Tile $tile
	 *
	 * @return Tile|null
	 *
	 * @deprecated
	 */
	public function getParentTile(Tile $tile)/*:?Tile*/ {
		if (!isset(self::$parent_tile_cache[$tile->getObjRefId()])) {
			try {
				self::$parent_tile_cache[$tile->getObjRefId()] = $this->getInstanceForObjRefId(self::dic()->tree()
					->getParentId($tile->getObjRefId()));
			} catch (Throwable $ex) {
				// Fix No node_id given!
				self::$parent_tile_cache[$tile->getObjRefId()] = null;
			}
		}

		return self::$parent_tile_cache[$tile->getObjRefId()];
	}


	/**
	 * @param int|null $obj_ref_id
	 *
	 * @return bool
	 *
	 * @deprecated
	 */
	public function isObject(/*?*/ int $obj_ref_id = null): bool {
		if (!isset(self::$is_object_cache[$obj_ref_id])) {
			self::$is_object_cache[$obj_ref_id] = ($obj_ref_id !== null && $obj_ref_id > 0 && $obj_ref_id !== intval(SYSTEM_FOLDER_ID)
				&& ($obj_ref_id === intval(ROOT_FOLDER_ID) || ($object = ilObjectFactory::getInstanceByRefId($obj_ref_id, false)) !== false)
				&& !($object instanceof ilObjOrgUnit));
		}

		return self::$is_object_cache[$obj_ref_id];
	}
}
