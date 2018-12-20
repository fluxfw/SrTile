<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class SrTileFavoritesGUI
 *
 * @ilCtrl_isCalledBy SrTileFavoritesGUI: ilUIPluginRouterGUI
 */
class SrTileFavoritesGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const CMD_ADD_TO_FAVORITES = "addToFavorites";
	const CMD_REMOVE_FROM_FAVORITES = "removeFromFavorites";
	const LANG_MODULE_FAVORITES = "favorites";
	/**
	 * @var Tile
	 */
	protected $tile;


	/**
	 * SrTileFavoritesGUI constructor
	 */
	public function __construct() {
		$this->tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());
	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!($this->tile->getProperties()->getShowFavoritesIcon() === Tile::SHOW_TRUE
			&& self::access()->hasReadAccess($this->tile->getObjRefId()))) {
			return;
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_ADD_TO_FAVORITES:
					case self::CMD_REMOVE_FROM_FAVORITES:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 */
	protected function addToFavorites()/*: void*/ {
		$parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

		self::ilias()->favorites(self::dic()->user())->addToFavorites($this->tile->getObjRefId());

		ilUtil::sendSuccess(self::plugin()->translate("added_to_favorites", self::LANG_MODULE_FAVORITES), true);

		if ($parent_ref_id !== NULL) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}


	/**
	 *
	 */
	protected function removeFromFavorites()/*: void*/ {
		$parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

		self::ilias()->favorites(self::dic()->user())->removeFromFavorites($this->tile->getObjRefId());

		ilUtil::sendSuccess(self::plugin()->translate("removed_from_favorites", self::LANG_MODULE_FAVORITES), true);

		if ($parent_ref_id !== NULL) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}
}
