<?php

namespace srag\Plugins\SrTile\TileList;

use ilException;
use ilObjUser;
use srag\Plugins\SrTile\Tile\Tile;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListDesktop extends TileListAbstract {

	/**
	 * @var int
	 */
	protected $usr_id;
	/**
	 * @var self[]
	 */
	protected static $instances = array();


	/**
	 * TileListDesktop constructor.
	 *
	 * @param int $usr_id
	 *
	 * @throws ilException
	 */
	private function __construct(int $usr_id) /*:void*/ {

		if(!ilObjUser::_exists($usr_id)) {
			throw new ilException("User does not exist.");
		}

		$this->usr_id = $usr_id;
		$this->read();
	}


	/**
	 * @param int|NULL $usr_id
	 *
	 * @return TileListDesktop
	 * @throws ilException
	 */
	public static function getInstance(int $usr_id = NULL): self {

		if (self::$instances[$usr_id] == NULL) {
			return self::$instances[$usr_id] = new self($usr_id);
		}

		return self::$instances[$usr_id];
	}


	/**
	 *
	 */
	public function read() /*:void*/ {
		$usr_obj = new ilObjUser($this->usr_id);

		$desktop_items = $usr_obj->getDesktopItems();
		foreach ($desktop_items as $item) {
			$tile = Tile::getInstanceForObjRefId($item['ref_id']);
			if(is_object($tile)) {
				$this->addTile($tile);
			}
		}
	}
	/**
	 * @return int
	 */
	public function getUsrId(): int {
		return $this->usr_id;
	}


	/**
	 * @param int $usr_id
	 */
	public function setUsrId(int $usr_id) /*:void*/ {
		$this->usr_id = $usr_id;
	}
}