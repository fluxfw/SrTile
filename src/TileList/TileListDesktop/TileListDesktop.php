<?php

namespace srag\Plugins\SrTile\TileList\TileListDesktop;

use ilException;
use ilObjUser;
use srag\Plugins\SrTile\TileList\TileListAbstract;

/**
 * Class TileListDesktop
 *
 * @package srag\Plugins\SrTile\TileList\TileListDesktop
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListDesktop extends TileListAbstract {

	/**
	 * TileListDesktop constructor
	 *
	 * @param int $id
	 *
	 * @throws ilException
	 */
	protected function __construct(int $id) /*:void*/ {
		if (!ilObjUser::_exists($id)) {
			throw new ilException("User does not exist.");
		}

		parent::__construct($id);
	}


	/**
	 * @inheritdoc
	 */
	public function read(array $items = []) /*:void*/ {
		$usr_obj = new ilObjUser($this->getBaseId());

		$items = self::ilias()->favorites($usr_obj)->getFavorites();

		parent::read($items);
	}
}
