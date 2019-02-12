<#1>
<?php
\srag\Plugins\SrTile\Config\Config::updateDB();
\srag\Plugins\SrTile\Tile\Tile::updateDB();
\srag\Plugins\SrTile\Rating\Rating::updateDB();
\srag\Plugins\SrTile\ColorThiefCache\ColorThiefCache::updateDB();

// Create default values for top tile
if (\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID, false)->getTileId() === 0) {
	$tile = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID);
	$tile->setTileEnabledChildren(true);
	$tile->setActionsPosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_ACTIONS_POSITION);
	$tile->setActionsVerticalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_ACTIONS_VERTICAL_ALIGN);
	$tile->setApplyColorsToGlobalSkin(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_APPLY_COLORS_TO_GLOBAL_SKIN);
	$tile->setBackgroundColorType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_BACKGROUND_COLOR_TYPE);
	$tile->setBorderColorType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_BORDER_COLOR_TYPE);
	$tile->setBorderSize(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_BORDER_SIZE);
	$tile->setBorderSizeType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_BORDER_SIZE_TYPE);
	$tile->setEnableRating(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_ENABLE_RATING);
	$tile->setFontColorType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_FONT_COLOR_TYPE);
	$tile->setFontSize(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_FONT_SIZE);
	$tile->setFontSizeType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_FONT_SIZE_TYPE);
	$tile->setImagePosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_IMAGE_POSITION);
	$tile->setLabelHorizontalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_LABEL_HORIZONTAL_ALIGN);
	$tile->setLabelVerticalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_LABEL_VERTICAL_ALIGN);
	$tile->setLearningProgressPosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_LEARNING_PROGRESS_POSITION);
	$tile->setMargin(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_MARGIN);
	$tile->setMarginType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_MARGIN_TYPE);
	$tile->setObjectIconPosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_OBJECT_ICON_POSITION);
	$tile->setOpenObjWithOneChildDirect(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_OPEN_OBJ_WITH_ONE_CHILD_DIRECT);
	$tile->setRecommendMailTemplateType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_RECOMMENDATION_MAIL_TEMPLATE_TYPE);
	$tile->setShadow(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHADOW);
	$tile->setShowActions(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_ACTIONS);
	$tile->setShowDownloadCertificate(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_DOWNLOAD_CERTIFICATE);
	$tile->setShowFavoritesIcon(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_FAVORITES_ICON);
	$tile->setShowImageAsBackground(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_IMAGE_AS_BACKGROUND);
	$tile->setShowLearningProgress(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_LEARNING_PROGRESS);
	$tile->setShowLearningProgressLegend(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_LEARNING_PROGRESS_LEGEND);
	$tile->setShowLikesCount(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_LIKES_COUNT);
	$tile->setShowObjectTabs(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_OBJECT_TABS);
	$tile->setShowPreconditions(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_PRECONDITIONS);
	$tile->setShowRecommendIcon(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_RECOMMEND_ICON);
	$tile->setShowTitle(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_TITLE);
	$tile->setView(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_VIEW);
	$tile->store();
}
?>
<#2>
<?php
\srag\Plugins\SrTile\Tile\Tile::updateDB();

$tile = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID);
$tile->setView(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_VIEW);
$tile->store();

foreach (\srag\Plugins\SrTile\Tile\Tile::where([ "view" => 0 ])->get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */
	$tile->setView(\srag\Plugins\SrTile\Tile\Tile::VIEW_PARENT);
	$tile->store();
}
?>
<#3>
<?php
\srag\Plugins\SrTile\Tile\Tile::updateDB();

$tile = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID);
$tile->setShadow(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHADOW);
$tile->store();

foreach (\srag\Plugins\SrTile\Tile\Tile::where([ "shadow" => 0 ])->get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */
	$tile->setShadow(\srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT);
	$tile->store();
}
?>
<#4>
<?php
\srag\Plugins\SrTile\Tile\Tile::updateDB();

$tile = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID);
$tile->setShowLearningProgressFilter(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_SHOW_LEARNING_PROGRESS_FILTER);
$tile->store();

foreach (\srag\Plugins\SrTile\Tile\Tile::where([ "show_learning_progress_filter" => 0 ])->get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */
	$tile->setShowLearningProgressFilter(\srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT);
	$tile->store();
}
?>
<#5>
<?php
\srag\Plugins\SrTile\Tile\Tile::updateDB();

$tile = \srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID);
$tile->setApplyColorsToGlobalSkin(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_APPLY_COLORS_TO_GLOBAL_SKIN);
$tile->store();

foreach (\srag\Plugins\SrTile\Tile\Tile::where([ "apply_colors_to_global_skin" => 0 ])->get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */
	$tile->setApplyColorsToGlobalSkin(\srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT);
	$tile->store();
}
?>
