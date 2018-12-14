<?php

namespace srag\Plugins\SrTile\TileList\TileListContainer;

use ilException;
use srag\Plugins\SrTile\TileList\TileListAbstract;
use srag\Plugins\SrTile\TileList\TileListInterface;

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
	 * @var int
	 */
	protected $container_obj_ref_id;
	/**
	 * @var self[]
	 */
	protected static $instances = array();


	/**
	 * TileListContainer constructor
	 *
	 * @param int $container_obj_ref_id
	 *
	 * @throws ilException
	 */
	protected function __construct(int $container_obj_ref_id) /*:void*/ {
		$this->container_obj_ref_id = $container_obj_ref_id;

		parent::__construct();
	}


	/**
	 * @inheritdoc
	 */
	public static function getInstance(int $container_obj_ref_id = NULL): TileListInterface {
		if (self::$instances[$container_obj_ref_id] == NULL) {
			return self::$instances[$container_obj_ref_id] = new self($container_obj_ref_id);
		}

		return self::$instances[$container_obj_ref_id];
	}


	/**
	 * @inheritdoc
	 */
	public function read(array $items = []) /*:void*/ {
		$children = self::dic()->tree()->getChilds($this->container_obj_ref_id);

		parent::read($children);
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
