<?php

namespace srag\Plugins\SrTile\Tile;

use ilObjectFactory;
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
	 * @var Tile[]
	 */
	protected static $parent_tile_cache = [];
	/**
	 * @var bool[]
	 */
	protected static $is_object_cache = [];


	/**
	 * Tiles constructor
	 */
	private function __construct() {

	}


	/**
	 * @return int|null
	 */
	public function filterRefId()/*: ?int*/ {
		$ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

		if ($ref_id === NULL) {
			$param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

			$ref_id = explode('_', $param_target)[1];
		}

		$ref_id = intval($ref_id);

		if ($ref_id > 0) {
			return $ref_id;
		} else {
			return NULL;
		}
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return Tile
	 */
	public function getInstanceForObjRefId(int $obj_ref_id): Tile {
		if (!isset(self::$instances_by_ref_id[$obj_ref_id])) {
			$tile = Tile::where([ 'obj_ref_id' => $obj_ref_id ])->first();

			if ($tile === NULL) {
				$tile = new Tile();

				if ($this->isObject($obj_ref_id)) {
					$tile->setObjRefId($obj_ref_id);

					$tile->store();
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
	 */
	public function getParentTile(Tile $tile)/*:?Tile*/ {
		if (!isset(self::$parent_tile_cache[$tile->getObjRefId()])) {
			try {
				self::$parent_tile_cache[$tile->getObjRefId()] = $this->getInstanceForObjRefId(self::dic()->tree()
					->getParentId($tile->getObjRefId()));
			} catch (Throwable $ex) {
				// Fix No node_id given!
				self::$parent_tile_cache[$tile->getObjRefId()] = NULL;
			}
		}

		return self::$parent_tile_cache[$tile->getObjRefId()];
	}


	/**
	 * @param int|null $ref_id
	 *
	 * @return bool
	 */
	public function isObject(/*?*/
		int $ref_id = NULL): bool {
		if (!isset(self::$is_object_cache[$ref_id])) {
			self::$is_object_cache[$ref_id] = ($ref_id !== NULL && $ref_id > 0 && $ref_id !== intval(SYSTEM_FOLDER_ID)
				&& ($ref_id === intval(ROOT_FOLDER_ID) || ilObjectFactory::getInstanceByRefId($ref_id, false) !== false));
		}

		return self::$is_object_cache[$ref_id];
	}
}
