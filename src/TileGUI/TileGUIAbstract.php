<?php

namespace srag\Plugins\SrTile\TileGUI;

use ilAdvancedSelectionListGUI;
use ilObject;
use ilObjRootFolderGUI;
use ilRepositoryGUI;
use ilSrTilePlugin;
use ilUIPluginRouterGUI;
use srag\CustomInputGUIs\SrTile\CustomInputGUIsTrait;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use SrTileFavoritesGUI;
use SrTileRatingGUI;
use SrTileRecommendGUI;
use srag\Plugins\SrTile\LearningProgressBar\LearningProgressBar;

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
	use CustomInputGUIsTrait;
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
		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileFavoritesGUI::class, "ref_id", $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRatingGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRatingGUI::class, "ref_id", $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRecommendGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(SrTileRecommendGUI::class, "ref_id", $this->tile->getObjRefId());

		$tpl = self::plugin()->template("Tile/tile.html");
		$tpl->setCurrentBlock("tile");

		$tpl->setVariable("TILE_ID", $this->tile->getTileId());

		$tpl->setVariable("OBJECT_TYPE", ($this->tile->getProperties()->getIlObject() !== NULL ? $this->tile->getProperties()->getIlObject()
			->getType() : ""));

		if ($this->tile->getProperties()->getShowTitle() === Tile::SHOW_TRUE) {
			$tpl->setVariable("TITLE", $this->tile->getProperties()->getTitle());
		}
		$tpl->setVariable("TITLE_HORIZONTAL_ALIGN", $this->tile->getProperties()->getLabelHorizontalAlign());
		$tpl->setVariable("TITLE_VERTICAL_ALIGN", $this->tile->getProperties()->getLabelVerticalAlign());

		if (self::access()->hasOpenAccess($this->tile)) {
			$tpl->setVariable("LINK", 'onclick="' . $this->tile->getProperties()->getOnClickLink() . '"');

			if (self::ilias()->favorites(self::dic()->user())->enabled()
				&& $this->tile->getProperties()->getShowFavoritesIcon() === Tile::SHOW_TRUE) {
				$tpl_favorite = self::plugin()->template("Favorite/favorite.html");

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
				$tpl_rating = self::plugin()->template("Rating/rating.html");

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

				if ($this->tile->getProperties()->getShowLikesCount() === Tile::SHOW_TRUE) {
					$likes_count = self::rating(self::dic()->user())->getLikesCount($this->tile->getObjRefId());

					if ($likes_count > 0) {
						$tpl_likes_count = self::plugin()->template("Favorite/likes_count.html");
						$tpl_likes_count->setVariable("LIKES_COUNT", $likes_count);
						$tpl_rating->setVariable("LIKES_COUNT", self::output()->getHTML($tpl_likes_count));
					}
				}

				$tpl->setVariable("RATING", self::output()->getHTML($tpl_rating));
			}

			if ($this->tile->getProperties()->getShowRecommendIcon() === Tile::SHOW_TRUE
				&& !empty($this->tile->getProperties()->getRecommendMailTemplate())
				&& self::access()->hasReadAccess($this->tile->getObjRefId())) {
				$tpl_recommend = self::plugin()->template("Recommend/recommend.html");

				$tpl_recommend->setVariable("RECOMMEND_LINK", self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					SrTileRecommendGUI::class
				], SrTileRecommendGUI::CMD_ADD_RECOMMEND, "", true));
				$tpl_recommend->setVariable("RECOMMEND_TEXT", self::plugin()->translate("recommend", SrTileRecommendGUI::LANG_MODULE_RECOMMENDATION));
				$tpl_recommend->setVariable("RECOMMEND_IMAGE_PATH", self::plugin()->directory() . "/templates/images/recommend.svg");

				$tpl->setVariable("RECOMMEND", self::output()->getHTML($tpl_recommend));
			}

			if (self::ilias()->learningProgress(self::dic()->user())->enabled()
				&& $this->tile->hasLearningProgress()) {
				switch ($this->tile->getProperties()->getShowLearningProgress()
				) {
					case Tile::LEARNING_PROGRESS_ICON:
						$icon = self::ilias()->learningProgress(self::dic()->user())->getIcon($this->tile->getObjRefId());

						$tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::dic()->ui()->factory()->image()
							->standard($icon, "")));

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_POSITION", $this->tile->getProperties()
							->getLearningProgressPosition());

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_TEXT", self::ilias()->learningProgress(self::dic()->user())
							->getText($this->tile->getObjRefId()));

						$tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
						break;

					case Tile::LEARNING_PROGRESS_BAR:
						$learning_progress_bar = new LearningProgressBar(self::dic()->user()->getId(), $this->tile->getObjRefId());

						$tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::customInputGUIs()->progressMeter()
							->mini($learning_progress_bar->getTotalObjects(), $learning_progress_bar->getCompletedObjects())));

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_POSITION", $this->tile->getProperties()
							->getLearningProgressPosition());

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_TEXT", self::ilias()->learningProgress(self::dic()->user())
							->getText($this->tile->getObjRefId()));

						$tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
						break;

					default:
						break;
				}
			}
		} else {
			$tpl->setVariable("DISABLED", " tile_disabled");
		}

		$tpl_image = self::plugin()->template("Tile/image.html");
		$tpl_image->setVariable("IMAGE", $this->tile->getProperties()->getImage());
		$tpl->setVariable("IMAGE", self::output()->getHTML($tpl_image));

		$tpl->setVariable("IMAGE_POSITION", $this->tile->getProperties()->getImagePosition());
		$tpl->setVariable("IMAGE_SHOW_AS_BACKGROUND", $this->tile->getProperties()->getShowImageAsBackground());

		if ($this->tile->getProperties()->getShowActions() === Tile::SHOW_TRUE && self::access()->hasWriteAccess($this->tile->getObjRefId())) {
			$tpl->setVariable("ACTIONS", $this->getActions());
		}
		$tpl->setVariable("ACTIONS_POSITION", $this->tile->getProperties()->getActionsPosition());
		$tpl->setVariable("ACTIONS_VERTICAL_ALIGN", $this->tile->getProperties()->getActionsVerticalAlign());

		if ($this->tile->getProperties()->getObjectIconPosition() !== Tile::POSITION_NONE) {
			$icon = ilObject::_getIcon(($this->tile->getProperties()->getIlObject() !== NULL ? $this->tile->getProperties()->getIlObject()
				->getId() : NULL), "small");
			if (file_exists($icon)) {
				$tpl_object_icon = self::plugin()->template("Object/object_icon.html");

				$tpl_object_icon->setVariable("OBJECT_ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));

				$tpl_object_icon->setVariable("OBJECT_ICON_POSITION", $this->tile->getProperties()->getObjectIconPosition());

				$tpl->setVariable("OBJECT_ICON", self::output()->getHTML($tpl_object_icon));
			}
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
}
