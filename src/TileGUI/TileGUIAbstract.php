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
use SrTileRatingGUI;

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
		$parent_tile = self::tiles()->getParentTile($this->tile);

		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "ref_id", $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRatingGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRatingGUI::class, "ref_id", $this->tile->getObjRefId());

		$tpl = self::plugin()->template("Tile/tpl.tile.html");
		$tpl->setCurrentBlock("tile");

		$tpl->setVariable("TILE_ID", $this->tile->getTileId());

		$tpl->setVariable("OBJECT_TYPE", ($this->tile->getIlObject() !== NULL ? $this->tile->getIlObject()->getType() : ""));

		if ($this->tile->getProperties()->getShowTitle() === Tile::SHOW_TRUE) {
			$tpl->setVariable("TITLE", $this->tile->getProperties()->getTitle());
		}
		$tpl->setVariable("TITLE_HORIZONTAL_ALIGN", $this->tile->getProperties()->getLabelHorizontalAlign());
		$tpl->setVariable("TITLE_VERTICAL_ALIGN", $this->tile->getProperties()->getLabelVerticalAlign());

		if (self::access()->hasOpenAccess($this->tile)) {
			$tpl->setVariable("LINK", ' onclick="location.href=\'' . htmlspecialchars($this->tile->returnLink()) . '\'"');

			if ($this->tile->getProperties()->getShowFavoritesIcon() === Tile::SHOW_TRUE
				&& self::access()->hasReadAccess($this->tile->getObjRefId())) {
				$tpl_favorite = self::plugin()->template("Tile/tpl.favorite.html");

				if (self::ilias()->favorites(self::dic()->user())->hasFavorite($this->tile->getObjRefId())) {
					$tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						SrTileFavoritesGUI::class
					], SrTileFavoritesGUI::CMD_REMOVE_FROM_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_TEXT", self::plugin()
						->translate("remove_from_favorites", SrTileFavoritesGUI::LANG_MODULE_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/favorite.svg");
				} else {
					$tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						SrTileFavoritesGUI::class
					], SrTileFavoritesGUI::CMD_ADD_TO_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_TEXT", self::plugin()
						->translate("add_to_favorites", SrTileFavoritesGUI::LANG_MODULE_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unfavorite.svg");
				}

				$tpl->setVariable("FAVORITE", self::output()->getHTML($tpl_favorite));
			}

			if ($this->tile->getProperties()->getEnableRating() === Tile::SHOW_TRUE
				&& self::access()->hasReadAccess($this->tile->getObjRefId())) {
				$tpl_rating = self::plugin()->template("Tile/tpl.rating.html");

				if (self::rating(self::dic()->user())->hasLike($this->tile->getObjRefId())) {
					$tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						SrTileRatingGUI::class
					], SrTileRatingGUI::CMD_UNLIKE));
					$tpl_rating->setVariable("RATING_TEXT", self::plugin()->translate("unlike", SrTileRatingGUI::LANG_MODULE_RATING));
					$tpl_rating->setVariable("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/like.svg");
				} else {
					$tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						SrTileRatingGUI::class
					], SrTileRatingGUI::CMD_LIKE));
					$tpl_rating->setVariable("RATING_TEXT", self::plugin()->translate("like", SrTileRatingGUI::LANG_MODULE_RATING));
					$tpl_rating->setVariable("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unlike.svg");
				}

				if ($this->tile->getProperties()->getShowRatingCount() === Tile::SHOW_TRUE) {
					$likes_count = self::rating(self::dic()->user())->getLikesCount($this->tile->getObjRefId());

					if ($likes_count > 0) {
						$tpl_likes_count = self::plugin()->template("Tile/tpl.likes_count.html");
						$tpl_likes_count->setVariable("LIKES_COUNT", $likes_count);
						$tpl_rating->setVariable("LIKES_COUNT", self::output()->getHTML($tpl_likes_count));
					}
				}

				$tpl->setVariable("RATING", self::output()->getHTML($tpl_rating));
			}
		} else {
			$tpl->setVariable("DISABLED", " tile_disabled");
		}

		$tpl->setVariable("IMAGE", $this->tile->getProperties()->getImage());
		$tpl->setVariable("IMAGE_POSITION", $this->tile->getProperties()->getImagePosition());

		if ($this->tile->getProperties()->getShowActions() === Tile::SHOW_TRUE && self::access()->hasWriteAccess($this->tile->getObjRefId())) {
			$tpl->setVariable("ACTIONS", $this->getActions());
		}
		$tpl->setVariable("ACTIONS_POSITION", $this->tile->getProperties()->getActionsPosition());
		$tpl->setVariable("ACTIONS_VERTICAL_ALIGN", $this->tile->getProperties()->getActionsVerticalAlign());

		$icon = ilObject::_getIcon(($this->tile->getIlObject() !== NULL ? $this->tile->getIlObject()->getId() : NULL), "small");
		if (file_exists($icon)) {
			$tpl->setVariable("OBJECT_ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));
		}
		$tpl->setVariable("OBJECT_ICON_POSITION", $this->tile->getProperties()->getObjectIconPosition());

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
}
