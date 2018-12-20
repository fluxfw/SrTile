<?php

namespace srag\Plugins\SrTile\Favorite;

use ilObject;
use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Favorites
 *
 * @package srag\Plugins\SrTile\Favorite
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Favorites {

	use SrTileTrait;
	use DICTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self[]
	 */
	protected static $instances = [];


	/**
	 * @param ilObjUser $user
	 *
	 * @return self
	 */
	public static function getInstance(ilObjUser $user): self {
		if (!isset(self::$instances[$user->getId()])) {
			self::$instances[$user->getId()] = new self($user);
		}

		return self::$instances[$user->getId()];
	}


	/**
	 * @var ilObjUser
	 */
	protected $user;


	/**
	 * Favorites constructor
	 *
	 * @param ilObjUser $user
	 */
	private function __construct(ilObjUser $user) {
		$this->user = $user;
	}


	/**
	 * @return bool
	 */
	public function enabled(): bool {
		return (boolval(self::dic()->settings()->get("disable_my_offers")) === false);
	}


	/**
	 * @return array
	 */
	public function getFavorites(): array {
		$favorites = $this->user->getDesktopItems();

		$children = array_map(function (array $favorite): array {
			return [
				"child" => $favorite["ref_id"],
				"type" => $favorite["type"],
				"description" => $favorite["description"],
				"position" => NULL,
				"path" => NULL,
				"title" => $favorite["title"],
				"parent_ref" => $favorite["parent_ref"]
			];
		}, $favorites);

		return $children;
	}


	/**
	 * @param int $ref_id
	 *
	 * @return bool
	 */
	public function hasFavorite(int $ref_id): bool {
		return boolval($this->user->isDesktopItem($ref_id, ilObject::_lookupType($ref_id, true)));
	}


	/**
	 * @param int $ref_id
	 */
	public function addToFavorites(int $ref_id)/*: void*/ {
		$this->user->addDesktopItem($ref_id, ilObject::_lookupType($ref_id, true));
	}


	/**
	 * @param int $ref_id
	 */
	public function removeFromFavorites(int $ref_id)/*: void*/ {
		$this->user->dropDesktopItem($ref_id, ilObject::_lookupType($ref_id, true));
	}
}
