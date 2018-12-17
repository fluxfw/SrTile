<?php

namespace srag\Plugins\SrTile\TileGUI;

use ilAdvancedSelectionListGUI;
use ilObject;
use ilObjRootFolderGUI;
use ilRepositoryGUI;
use ilSrTilePlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use SrTileFavoritesGUI;

/**
 * Class TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
abstract class TileGUIAbstract implements TileGUIInterface {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var Tile
	 */
	private $tile;


	/**
	 * TileContainerGUI constructor
	 *
	 * @param Tile $tile
	 */
	public function __construct(Tile $tile) {
		$this->tile = $tile;
	}


	/**
	 * @inheritdoc
	 */
	public function render(): string {
		$this->setCardColor();

		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "ref_id", $this->tile->getObjRefId());

		$tpl = self::plugin()->template("Tile/tpl.tile.html");
		$tpl->setCurrentBlock("tile");
		$tpl->setVariable("TILE_ID", $this->tile->getTileId());
		$tpl->setVariable("LABEL", ($this->tile->getIlObject() !== NULL ? $this->tile->getIlObject()->getTitle() : ""));
		if (self::access()->hasOpenAccess($this->tile)) {
			$tpl->setVariable("LINK", ' onclick="location.href=\'' . htmlspecialchars($this->tile->returnLink()) . '\'"');
		} else {
			$tpl->setVariable("DISABLED", " tile_disabled");
		}

		$tpl->setVariable("IMAGE", $this->tile->getImage());

		if (self::access()->hasWriteAccess($this->tile->getObjRefId())) {
			$tpl->setVariable("ACTIONS", $this->getActions());
		}

		$icon = ilObject::_getIcon(($this->tile->getIlObject() !== NULL ? $this->tile->getIlObject()->getId() : NULL), "small");
		if (file_exists($icon)) {
			$tpl->setVariable("ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));
		}

		if (self::ilias()->favorites(self::dic()->user())->hasFavorite($this->tile->getObjRefId())) {
			$tpl->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
				ilUIPluginRouterGUI::class,
				SrTileFavoritesGUI::class
			], SrTileFavoritesGUI::CMD_REMOVE_FROM_FAVORITES));
			$tpl->setVariable("FAVORITE_TEXT", self::plugin()->translate("remove_from_favorites", SrTileFavoritesGUI::LANG_MODULE_FAVORITES));
			$tpl->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/favorite.svg");
		} else {
			$tpl->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
				ilUIPluginRouterGUI::class,
				SrTileFavoritesGUI::class
			], SrTileFavoritesGUI::CMD_ADD_TO_FAVORITES));
			$tpl->setVariable("FAVORITE_TEXT", self::plugin()->translate("add_to_favorites", SrTileFavoritesGUI::LANG_MODULE_FAVORITES));
			$tpl->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unfavorite.svg");
		}

		$tpl->parseCurrentBlock();

		return self::output()->getHTML($tpl);
	}


	/**
	 * @inheritdoc
	 */
	public function getActions(): string {
		$advanced_selection_list = new ilAdvancedSelectionListGUI();
		$advanced_selection_list->setAsynch(true);
		$advanced_selection_list->setId('act_' . $this->tile->getObjRefId() . '_tile_' . $this->tile->getTileId());
		$advanced_selection_list->setAsynchUrl($this->getActionAsyncUrl());

		return self::output()->getHTML($advanced_selection_list);
	}


	/**
	 * @inheritdoc
	 */
	public function getActionAsyncUrl(): string {
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "ref_id", ROOT_FOLDER_ID);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", $this->tile->getObjRefId());

		$async_url = self::dic()->ctrl()->getLinkTargetByClass(array(
			ilRepositoryGUI::class,
			ilObjRootFolderGUI::class
		), "getAsynchItemList", "", true, false);

		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "ref_id", NULL);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", NULL);

		return $async_url;
	}


	/**
	 * @inheritdoc
	 */
	public function setCardColor()/*: void*/ {
		// TODO: Not work?!
		if (!empty($this->tile->getLevelColor())) {
			$id = "#sr-tile-_" . $this->tile->getTileId();

			$css = $id . '{' . $this->tile->getColor() . '}';
			self::dic()->mainTemplate()->addInlineCss($css);
		}
	}
}
