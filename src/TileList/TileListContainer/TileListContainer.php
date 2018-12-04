<?php

namespace srag\Plugins\SrTile\TileList;

use ilException;
use ilObject;
use srag\Plugins\SrTile\Tile\Tile;

;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListContainer extends TileListAbstract {

	/**
	 * @var int
	 */
	protected $container_obj_ref_id;
	/**
	 * @var self[]
	 */
	protected static $instances = array();
	/**
	 * All possible object types
	 *
	 * @var array
	 */
	public static $possible_obj_types = array(
		'root',
		'cat',
		'crs',
		'grp'
	);


	/**
	 * TileListContainer constructor.
	 *
	 * @param int $container_obj_ref_id
	 *
	 * @throws ilException
	 */
	private function __construct(int $container_obj_ref_id) /*:void*/ {

		if (!in_array(ilObject::_lookupType($container_obj_ref_id, true), self::$possible_obj_types)) {
			return;
		}

		$this->container_obj_ref_id = $container_obj_ref_id;
		$this->read();
	}


	/**
	 * @param int $container_obj_ref_id
	 *
	 * @return TileListContainer
	 */
	public static function getInstance(int $container_obj_ref_id = NULL): self {

		if (self::$instances[$container_obj_ref_id] == NULL) {
			return self::$instances[$container_obj_ref_id] = new self($container_obj_ref_id);
		}

		return self::$instances[$container_obj_ref_id];
	}


	/**
	 * @return void
	 */
	public function read() /*:void*/ {
		$children = self::dic()->tree()->getChilds($this->container_obj_ref_id);
		if (count($children) > 0) {
			foreach ($children as $child) {
				if ($child['child'] > 0) {
					$tile = Tile::getInstanceForObjRefId($child['child']);
					if ($tile instanceof Tile && self::dic()->filesystem()->web()->has($tile->returnRelativeImagePath(true))) {
						$this->addTile($tile);
					}
				}
			}
		}
	}


	/**
	 * @return int
	 */
	public function getContainerObjRefId(): int {
		return $this->container_obj_ref_id;
	}


	/**
	 * @param int $container_obj_ref_id
	 */
	public function setContainerObjRefId(int $container_obj_ref_id) {
		$this->container_obj_ref_id = $container_obj_ref_id;
	}
}