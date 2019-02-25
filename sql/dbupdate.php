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

	if ($tile->getActionsPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setActionsPosition(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getActionsPosition());
	}

	if ($tile->getActionsVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
		$tile->setActionsVerticalAlign(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getActionsVerticalAlign());
	}

	if ($tile->getApplyColorsToGlobalSkin() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setApplyColorsToGlobalSkin(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getApplyColorsToGlobalSkin());
	}

	if ($tile->getBackgroundColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setBackgroundColorType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getBackgroundColorType());
	}

	if ($tile->getBorderColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setBorderColorType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getBorderColorType());
	}

	if ($tile->getBorderSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setBorderSizeType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getBorderSizeType());
	}

	if ($tile->getEnableRating() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setEnableRating(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getEnableRating());
	}

	if ($tile->getFontColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setFontColorType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getFontColorType());
	}

	if ($tile->getFontSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setFontSizeType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getFontSizeType());
	}

	if ($tile->getImagePosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setImagePosition(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getImagePosition());
	}

	if ($tile->getLabelHorizontalAlign() === \srag\Plugins\SrTile\Tile\Tile::HORIZONTAL_ALIGN_PARENT) {
		$tile->setLabelHorizontalAlign(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getLabelHorizontalAlign());
	}

	if ($tile->getLabelVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
		$tile->setLabelVerticalAlign(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getLabelVerticalAlign());
	}

	if ($tile->getLearningProgressPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setLearningProgressPosition(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getLearningProgressPosition());
	}

	if ($tile->getMarginType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setMarginType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getMarginType());
	}

	if ($tile->getObjectIconPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setObjectIconPosition(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjectIconPosition());
	}

	if ($tile->getOpenObjWithOneChildDirect() === \srag\Plugins\SrTile\Tile\Tile::OPEN_PARENT) {
		$tile->setOpenObjWithOneChildDirect(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getOpenObjWithOneChildDirect());
	}

	if ($tile->getRecommendMailTemplateType() === \srag\Plugins\SrTile\Tile\Tile::MAIL_TEMPLATE_PARENT) {
		$tile->setRecommendMailTemplateType(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getRecommendMailTemplateType());
	}

	if ($tile->getShowActions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowActions(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowActions());
	}

	if ($tile->getShowDownloadCertificate() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowDownloadCertificate(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowDownloadCertificate());
	}

	if ($tile->getShowFavoritesIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowFavoritesIcon(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowFavoritesIcon());
	}

	if ($tile->getShowImageAsBackground() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowImageAsBackground(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowImageAsBackground());
	}

	if ($tile->getShowLearningProgress() === \srag\Plugins\SrTile\Tile\Tile::LEARNING_PROGRESS_PARENT) {
		$tile->setShowLearningProgress(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowLearningProgress());
	}

	if ($tile->getShowLearningProgressFilter() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLearningProgressFilter(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowLearningProgressFilter());
	}

	if ($tile->getShowLearningProgressLegend() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLearningProgressLegend(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowLearningProgressLegend());
	}

	if ($tile->getShowLikesCount() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLikesCount(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowLikesCount());
	}

	if ($tile->getShowObjectTabs() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowObjectTabs(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowObjectTabs());
	}

	if ($tile->getShowPreconditions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowPreconditions(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowPreconditions());
	}

	if ($tile->getShowRecommendIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowRecommendIcon(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowRecommendIcon());
	}

	if ($tile->getShowTitle() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowTitle(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getShowTitle());
	}

	if ($tile->getView() === \srag\Plugins\SrTile\Tile\Tile::VIEW_PARENT) {
		$tile->setView(\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getView());
	}

	if (!boolval($tile->tile_enabled_children)) {
		$tile->setView(\srag\Plugins\SrTile\Tile\Tile::VIEW_DISABLED);
	}

	$tile->store();
}
?>
