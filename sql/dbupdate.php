<#1>
<?php
//
?>
<#2>
<?php
//
?>
<#3>
<?php
//
?>
<#4>
<?php
//
?>
<#5>
<?php
//
?>
<#6>
<?php
\srag\Plugins\SrTile\Config\Config::updateDB();
\srag\Plugins\SrTile\Tile\Tile::updateDB();
\srag\Plugins\SrTile\Rating\Rating::updateDB();
\srag\Plugins\SrTile\ColorThiefCache\ColorThiefCache::updateDB();
\srag\Plugins\SrTile\Template\Template::updateDB();
?>
<#7>
<?php
foreach (\srag\Plugins\SrTile\Tile\Tile::orderBy("obj_ref_id", "asc")->get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */

	$parent = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile);
	if ($parent !== NULL) {

		if ($tile->getActionsPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
			$tile->setActionsPosition($parent->getActionsPosition());
		}

		if ($tile->getActionsVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
			$tile->setActionsVerticalAlign($parent->getActionsVerticalAlign());
		}

		if ($tile->getApplyColorsToGlobalSkin() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setApplyColorsToGlobalSkin($parent->getApplyColorsToGlobalSkin());
		}

		if ($tile->getBackgroundColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
			$tile->setBackgroundColorType($parent->getBackgroundColorType());

			if (empty($tile->getBackgroundColor())) {
				$tile->setBackgroundColor($parent->getBackgroundColor());
			}
		}

		if ($tile->getBorderColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
			$tile->setBorderColorType($parent->getBorderColorType());

			if (empty($tile->getBorderColor())) {
				$tile->setBorderColor($parent->getBorderColor());
			}
		}

		if ($tile->getBorderSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
			$tile->setBorderSizeType($parent->getBorderSizeType());

			if (empty($tile->getBorderSize())) {
				$tile->setBorderSize($parent->getBorderSize());
			}
		}

		if ($tile->getEnableRating() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setEnableRating($parent->getEnableRating());
		}

		if ($tile->getFontColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
			$tile->setFontColorType($parent->getFontColorType());

			if (empty($tile->getFontColor())) {
				$tile->setFontColor($parent->getFontColor());
			}
		}

		if ($tile->getFontSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
			$tile->setFontSizeType($parent->getFontSizeType());

			if (empty($tile->getFontSize())) {
				$tile->setFontSize($parent->getFontSize());
			}
		}

		if ($tile->getImagePosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
			$tile->setImagePosition($parent->getImagePosition());
		}

		if ($tile->getLabelHorizontalAlign() === \srag\Plugins\SrTile\Tile\Tile::HORIZONTAL_ALIGN_PARENT) {
			$tile->setLabelHorizontalAlign($parent->getLabelHorizontalAlign());
		}

		if ($tile->getLabelVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
			$tile->setLabelVerticalAlign($parent->getLabelVerticalAlign());
		}

		if ($tile->getLearningProgressPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
			$tile->setLearningProgressPosition($parent->getLearningProgressPosition());
		}

		if ($tile->getMarginType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
			$tile->setMarginType($parent->getMarginType());

			if (empty($tile->getMargin())) {
				$tile->setMargin($parent->getMargin());
			}
		}

		if ($tile->getObjectIconPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
			$tile->setObjectIconPosition($parent->getObjectIconPosition());
		}

		if ($tile->getOpenObjWithOneChildDirect() === \srag\Plugins\SrTile\Tile\Tile::OPEN_PARENT) {
			$tile->setOpenObjWithOneChildDirect($parent->getOpenObjWithOneChildDirect());
		}

		if ($tile->getRecommendMailTemplateType() === \srag\Plugins\SrTile\Tile\Tile::MAIL_TEMPLATE_PARENT) {
			$tile->setRecommendMailTemplateType($parent->getRecommendMailTemplateType());

			if (empty($tile->getRecommendMailTemplate())) {
				$tile->setRecommendMailTemplate($parent->getRecommendMailTemplate());
			}
		}

		if ($tile->getShadow() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShadow($parent->getShadow());
		}

		if ($tile->getShowActions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowActions($parent->getShowActions());
		}

		if ($tile->getShowDownloadCertificate() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowDownloadCertificate($parent->getShowDownloadCertificate());
		}

		if ($tile->getShowFavoritesIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowFavoritesIcon($parent->getShowFavoritesIcon());
		}

		if ($tile->getShowImageAsBackground() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowImageAsBackground($parent->getShowImageAsBackground());
		}

		if ($tile->getShowLearningProgress() === \srag\Plugins\SrTile\Tile\Tile::LEARNING_PROGRESS_PARENT) {
			$tile->setShowLearningProgress($parent->getShowLearningProgress());
		}

		if ($tile->getShowLearningProgressFilter() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowLearningProgressFilter($parent->getShowLearningProgressFilter());
		}

		if ($tile->getShowLearningProgressLegend() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowLearningProgressLegend($parent->getShowLearningProgressLegend());
		}

		if ($tile->getShowLikesCount() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowLikesCount($parent->getShowLikesCount());
		}

		if ($tile->getShowObjectTabs() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowObjectTabs($parent->getShowObjectTabs());
		}

		if ($tile->getShowPreconditions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowPreconditions($parent->getShowPreconditions());
		}

		if ($tile->getShowRecommendIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowRecommendIcon($parent->getShowRecommendIcon());
		}

		if ($tile->getShowTitle() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
			$tile->setShowTitle($parent->getShowTitle());
		}

		if ($tile->getView() === \srag\Plugins\SrTile\Tile\Tile::VIEW_PARENT) {
			$tile->setView($parent->getView());
		}
	}

	if (!boolval($tile->tile_enabled_children)) {
		$tile->setView(\srag\Plugins\SrTile\Tile\Tile::VIEW_DISABLED);
	}

	$tile->store();
}
?>
