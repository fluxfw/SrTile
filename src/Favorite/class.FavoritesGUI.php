<?php

namespace srag\Plugins\SrTile\Favorite;

use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class FavoritesGUI
 *
 * @package           srag\Plugins\SrTile\Favorite
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Favorite\FavoritesGUI: ilUIPluginRouterGUI
 */
class FavoritesGUI {

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
	 * FavoritesGUI constructor
	 */
	public function __construct() {
		$this->tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());
	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!(self::ilias()->favorites(self::dic()->user())->enabled() && $this->tile->getShowFavoritesIcon() === Tile::SHOW_TRUE)) {
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

		if (!empty($parent_ref_id)) {
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

		if (!empty($parent_ref_id)) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}
}
