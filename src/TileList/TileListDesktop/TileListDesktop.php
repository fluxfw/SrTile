<?php

namespace srag\Plugins\SrTile\TileList\TileListDesktop;

use ilException;
use ilObjUser;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\TileList\TileListAbstract;
use srag\Plugins\SrTile\TileList\TileListInterface;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\TileList\TileListDesktop
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
	 * TileListDesktop constructor
	 *
	 * @param int $usr_id
	 *
	 * @throws ilException
	 */
	private function __construct(int $usr_id) /*:void*/ {
		if (!ilObjUser::_exists($usr_id)) {
			throw new ilException("User does not exist.");
		}

		$this->usr_id = $usr_id;
		$this->read();
	}


	/**
	 * @inheritdoc
	 */
	public static function getInstance(int $usr_id = NULL): TileListInterface {
		if (self::$instances[$usr_id] == NULL) {
			return self::$instances[$usr_id] = new self($usr_id);
		}

		return self::$instances[$usr_id];
	}


	/**
	 * @inheritdoc
	 */
	public function read() /*:void*/ {
		$usr_obj = new ilObjUser($this->usr_id);

		$desktop_items = self::ilias()->favorites($usr_obj)->getFavorites();
		foreach ($desktop_items as $item) {
			$tile = self::tiles()->getInstanceForObjRefId($item['child']);
			if ($tile instanceof Tile && $tile->isTileEnabled()) {
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
