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
	 * @var TileListInterface[]
	 */
	protected static $instances = [];


	/**
	 * @inheritdoc
	 */
	public static function getInstance($param): TileListInterface {
		if (self::$instances[static::class] === NULL) {
			self::$instances[static::class] = new static($param);
		}

		return self::$instances[static::class];
	}


	/**
	 * @var array
	 */
	protected $obj_ref_ids = [];
	/**
	 * @var tile[]
	 */
	private $tiles = [];


	/**
	 * TileListAbstract constructor
	 */
	protected function __construct() {
		$this->read();
	}


	/**
	 * @inheritdoc
	 */
	public function addTile(Tile $tile)/*: void*/ {
		$this->tiles[$tile->getTileId()] = $tile;
	}


	/**
	 * @inheritdoc
	 */
	public function removeTile(int $tile_id)/*: void*/ {
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
	 *
	 */
	protected function read() /*: void*/ {
		$this->initObjRefIds();

		foreach ($this->obj_ref_ids as $obj_ref_id) {

			$tile = self::tiles()->getInstanceForObjRefId($obj_ref_id);

			if (self::access()->hasVisibleAccess($tile->getObjRefId())) {
				$this->addTile($tile);
			}
		}
	}


	/**
	 *
	 */
	protected abstract function initObjRefIds() /*: void*/
	;
}
