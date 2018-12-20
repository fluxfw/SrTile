<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class SrTileRatingGUI
 *
 * @ilCtrl_isCalledBy SrTileRatingGUI: ilUIPluginRouterGUI
 */
class SrTileRatingGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const CMD_LIKE = "like";
	const CMD_UNLIKE = "unlike";
	const LANG_MODULE_RATING = "rating";
	/**
	 * @var Tile
	 */
	protected $tile;


	/**
	 * SrTileRatingGUI constructor
	 */
	public function __construct() {
		$this->tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());
	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		if (!($this->tile->getProperties()->getEnableRating() === Tile::SHOW_TRUE
			&& self::access()->hasReadAccess($this->tile->getObjRefId()))) {
			return;
		}

		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_LIKE:
					case self::CMD_UNLIKE:
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
	protected function like()/*: void*/ {
		$parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

		self::rating(self::dic()->user())->like($this->tile->getObjRefId());

		ilUtil::sendSuccess(self::plugin()->translate("liked", self::LANG_MODULE_RATING), true);

		if ($parent_ref_id !== NULL) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}


	/**
	 *
	 */
	protected function unlike()/*: void*/ {
		$parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

		self::rating(self::dic()->user())->unlike($this->tile->getObjRefId());

		ilUtil::sendSuccess(self::plugin()->translate("unliked", self::LANG_MODULE_RATING), true);

		if ($parent_ref_id !== NULL) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}
}
