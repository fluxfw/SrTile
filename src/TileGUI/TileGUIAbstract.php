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
use srag\Plugins\SrTile\Certificate\CertificateGUI;
use srag\Plugins\SrTile\Favorite\FavoritesGUI;
use srag\Plugins\SrTile\Rating\RatingGUI;
use srag\Plugins\SrTile\Recommend\RecommendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Tile\Tiles;
use srag\Plugins\SrTile\Utils\SrTileTrait;

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
	protected $tile;


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
		if (self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId() ?? ROOT_FOLDER_ID)->getShowLearningProgressFilter()
			=== Tile::SHOW_TRUE) {

			$lp_filters = self::learningProgressFilters(self::dic()->user())->getFilter(intval(self::tiles()->filterRefId()));

			if (count($lp_filters) > 0) {
				if (!in_array(self::ilias()->learningProgress(self::dic()->user())->getStatus($this->tile->getObjRefId()), $lp_filters)) {
					return "";
				}
			}
		}

		self::dic()->ctrl()->setParameterByClass(FavoritesGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(FavoritesGUI::class, Tiles::GET_PARAM_REF_ID, $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(RatingGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(RatingGUI::class, Tiles::GET_PARAM_REF_ID, $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(RecommendGUI::class, "parent_ref_id", self::tiles()->filterRefId());
		self::dic()->ctrl()->setParameterByClass(RecommendGUI::class, Tiles::GET_PARAM_REF_ID, $this->tile->getObjRefId());

		$tpl = self::plugin()->template("Tile/tile.html");
		$tpl->setCurrentBlock("tile");

		$tpl->setVariable("TILE_ID", $this->tile->getTileId());

		$tpl->setVariable("OBJECT_TYPE", ($this->tile->_getIlObject() !== NULL ? $this->tile->_getIlObject()->getType() : ""));

		if ($this->tile->getShowTitle() === Tile::SHOW_TRUE) {
			$tpl->setVariable("TITLE", $this->tile->_getTitle());
		}
		$tpl->setVariable("TITLE_HORIZONTAL_ALIGN", $this->tile->getLabelHorizontalAlign());
		$tpl->setVariable("TITLE_VERTICAL_ALIGN", $this->tile->getLabelVerticalAlign());

		if (self::access()->hasOpenAccess($this->tile)) {
			$tpl->setVariable("LINK", $this->tile->_getOnClickLink());

			if (self::ilias()->favorites(self::dic()->user())->enabled()
				&& $this->tile->getShowFavoritesIcon() === Tile::SHOW_TRUE) {
				$tpl_favorite = self::plugin()->template("Favorite/favorite.html");

				if (self::ilias()->favorites(self::dic()->user())->hasFavorite($this->tile->getObjRefId())) {
					$tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						FavoritesGUI::class
					], FavoritesGUI::CMD_REMOVE_FROM_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_TEXT", self::plugin()
						->translate("remove_from_favorites", FavoritesGUI::LANG_MODULE_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/favorite.svg");
				} else {
					$tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						FavoritesGUI::class
					], FavoritesGUI::CMD_ADD_TO_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_TEXT", self::plugin()->translate("add_to_favorites", FavoritesGUI::LANG_MODULE_FAVORITES));
					$tpl_favorite->setVariable("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unfavorite.svg");
				}

				$tpl->setVariable("FAVORITE", self::output()->getHTML($tpl_favorite));
			}

			if ($this->tile->getEnableRating() === Tile::SHOW_TRUE
				&& self::access()->hasReadAccess($this->tile->getObjRefId())) {
				$tpl_rating = self::plugin()->template("Rating/rating.html");

				if (self::rating(self::dic()->user())->hasLike($this->tile->getObjRefId())) {
					$tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						RatingGUI::class
					], RatingGUI::CMD_UNLIKE));
					$tpl_rating->setVariable("RATING_TEXT", self::plugin()->translate("unlike", RatingGUI::LANG_MODULE_RATING));
					$tpl_rating->setVariable("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/like.svg");
				} else {
					$tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						RatingGUI::class
					], RatingGUI::CMD_LIKE));
					$tpl_rating->setVariable("RATING_TEXT", self::plugin()->translate("like", RatingGUI::LANG_MODULE_RATING));
					$tpl_rating->setVariable("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unlike.svg");
				}

				if ($this->tile->getShowLikesCount() === Tile::SHOW_TRUE) {
					$likes_count = self::rating(self::dic()->user())->getLikesCount($this->tile->getObjRefId());

					if ($likes_count > 0) {
						$tpl_likes_count = self::plugin()->template("Rating/likes_count.html");
						$tpl_likes_count->setVariable("LIKES_COUNT", $likes_count);
						$tpl_rating->setVariable("LIKES_COUNT", self::output()->getHTML($tpl_likes_count));
					}
				}

				$tpl->setVariable("RATING", self::output()->getHTML($tpl_rating));
			}

			if ($this->tile->getShowRecommendIcon() === Tile::SHOW_TRUE
				&& !empty($this->tile->getRecommendMailTemplate())
				&& self::access()->hasReadAccess($this->tile->getObjRefId())) {
				$tpl_recommend = self::plugin()->template("Recommend/recommend.html");

				$tpl_recommend->setVariable("RECOMMEND_LINK", self::dic()->ctrl()->getLinkTargetByClass([
					ilUIPluginRouterGUI::class,
					RecommendGUI::class
				], RecommendGUI::CMD_ADD_RECOMMEND, "", true));
				$tpl_recommend->setVariable("RECOMMEND_TEXT", self::plugin()->translate("recommend", RecommendGUI::LANG_MODULE_RECOMMENDATION));
				$tpl_recommend->setVariable("RECOMMEND_IMAGE_PATH", self::plugin()->directory() . "/templates/images/recommend.svg");

				$tpl->setVariable("RECOMMEND", self::output()->getHTML($tpl_recommend));
			}

			if (self::ilias()->learningProgress(self::dic()->user())->hasLearningProgress($this->tile->getObjRefId())) {
				switch ($this->tile->getShowLearningProgress()) {
					case Tile::LEARNING_PROGRESS_ICON:
						$icon = self::ilias()->learningProgress(self::dic()->user())->getIcon($this->tile->getObjRefId());

						$tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::dic()->ui()->factory()->image()
							->standard($icon, "")));

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_POSITION", $this->tile->getLearningProgressPosition());

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_TEXT", self::ilias()->learningProgress(self::dic()->user())
							->getText($this->tile->getObjRefId()));

						if ($this->tile->getLearningProgressPosition() === Tile::POSITION_ON_THE_ICONS) {
							$tpl->setVariable("LEARNING_PROGRESS_ON_THE_ICONS", self::output()->getHTML($tpl_learning_progress));
						} else {
							$tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
						}
						break;

					case Tile::LEARNING_PROGRESS_METER:
						$learning_progress_bar = self::ilias()->learningProgressBar(self::dic()->user(), $this->tile->getObjRefId());

						$tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::customInputGUIs()->progressMeter()
							->mini($learning_progress_bar->getTotalObjects(), $learning_progress_bar->getCompletedObjects())));

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_POSITION", $this->tile->getLearningProgressPosition());

						$tpl_learning_progress->setVariable("LEARNING_PROGRESS_TEXT", self::ilias()->learningProgress(self::dic()->user())
							->getText($this->tile->getObjRefId()));

						if ($this->tile->getLearningProgressPosition() === Tile::POSITION_ON_THE_ICONS) {
							$tpl->setVariable("LEARNING_PROGRESS_ON_THE_ICONS", self::output()->getHTML($tpl_learning_progress));
						} else {
							$tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
						}
						break;

					default:
						break;
				}
			}
		} else {
			$tpl->setVariable("DISABLED", " tile_disabled");
		}

		$tpl_image = self::plugin()->template("Tile/image.html");
		$tpl_image->setVariable("IMAGE", "./" . $this->tile->getImagePathWithCheck());
		$tpl->setVariable("IMAGE", self::output()->getHTML($tpl_image));

		$tpl->setVariable("IMAGE_POSITION", $this->tile->getImagePosition());
		$tpl->setVariable("IMAGE_SHOW_AS_BACKGROUND", $this->tile->getShowImageAsBackground());

		if ($this->tile->getShowActions() === Tile::SHOW_TRUE && self::access()->hasWriteAccess($this->tile->getObjRefId())) {
			$tpl->setVariable("ACTIONS", $this->getActions());
		}
		$tpl->setVariable("ACTIONS_POSITION", $this->tile->getActionsPosition());
		$tpl->setVariable("ACTIONS_VERTICAL_ALIGN", $this->tile->getActionsVerticalAlign());

		if ($this->tile->getObjectIconPosition() !== Tile::POSITION_NONE) {
			$icon = ilObject::_getIcon(($this->tile->_getIlObject() !== NULL ? $this->tile->_getIlObject()->getId() : NULL), "small");
			if (file_exists($icon)) {
				$tpl_object_icon = self::plugin()->template("Object/object_icon.html");

				$tpl_object_icon->setVariable("OBJECT_ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));

				$tpl_object_icon->setVariable("OBJECT_ICON_POSITION", $this->tile->getObjectIconPosition());

				$tpl->setVariable("OBJECT_ICON", self::output()->getHTML($tpl_object_icon));
			}
		}

		if ($this->tile->getShowPreconditions() === Tile::SHOW_TRUE) {
			if (count(self::ilias()->courses()->getPreconditions($this->tile->getObjRefId())) > 0) {
				$tpl_preconditions = self::plugin()->template("Preconditions/preconditions.html");

				$tpl_preconditions->setVariable("PRECONDITIONS_TEXT", self::plugin()->translate("preconditions", TileGUI::LANG_MODULE_TILE));
				$tpl_preconditions->setVariable("PRECONDITIONS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/preconditions.svg");

				self::dic()->ctrl()->setParameterByClass(TileGUI::class, Tiles::GET_PARAM_REF_ID, $this->tile->getObjRefId());
				$popover = self::dic()->ui()->factory()->popover()->standard(self::dic()->ui()->factory()->legacy(""))
					->withAsyncContentUrl(str_replace("\\", "\\\\", self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						TileGUI::class
					], TileGUI::GET_PRECONDITIONS, "", true)));

				// Use a fake button to use clickable open popover. Set the button id on the info image
				$button = self::dic()->ui()->factory()->button()->standard("", "")->withOnClick($popover->getShowSignal());
				$button_html = self::output()->getHTML($button);
				$button_id = [];
				preg_match('/id="([a-z0-9_]+)"/', $button_html, $button_id);
				if (is_array($button_id) && count($button_id) > 1) {
					$button_id = $button_id[1];
					$tpl_preconditions->setVariable("BUTTON_ID", $button_id);
				}

				$tpl->setVariable("PRECONDITIONS", self::output()->getHTML([ $tpl_preconditions, $popover ]));
			}
		}

		if (self::ilias()->certificates(self::dic()->user(), $this->tile->getObjRefId())->enabled()
			&& $this->tile->getShowDownloadCertificate() === Tile::SHOW_TRUE) {


			$tpl->setVariable("CERTIFICATE", self::output()->getHTML(new CertificateGUI(self::dic()->user(), $this->tile->getObjRefId())));
		}

		$tpl->setVariable("SHADOW", $this->tile->getShadow());

		$tpl->parseCurrentBlock();

		return self::output()->getHTML($tpl);
	}


	/**
	 * @inheritdoc
	 */
	public function getActions(): string {
		if (self::dic()->ctrl()->isAsynch()) {
			// Hide because not work for asynch asynch load (Preconditions) - Some missing javascript int call on asynch
			return "";
		}

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
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, Tiles::GET_PARAM_REF_ID, ROOT_FOLDER_ID);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", $this->tile->getObjRefId());

		$async_url = self::dic()->ctrl()->getLinkTargetByClass(array(
			ilRepositoryGUI::class,
			ilObjRootFolderGUI::class
		), "getAsynchItemList", "", true, false);

		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, Tiles::GET_PARAM_REF_ID, NULL);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", NULL);

		return $async_url;
	}
}
