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
foreach (\srag\Plugins\SrTile\Tile\Tile::get() as $tile) {
	/**
	 * @var \srag\Plugins\SrTile\Tile\Tile $tile
	 */

	if ($tile->getActionsPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setActionsPosition(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT actions_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["actions_position"]);
	}

	if ($tile->getActionsVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
		$tile->setActionsVerticalAlign(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT actions_vertical_align FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["actions_vertical_align"]);
	}

	if ($tile->getApplyColorsToGlobalSkin() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setApplyColorsToGlobalSkin(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT apply_colors_to_global_skin FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["apply_colors_to_global_skin"]);
	}

	if ($tile->getBackgroundColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setBackgroundColorType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT background_color_type FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["background_color_type"]);
	}

	if ($tile->getBorderColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setBorderColorType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT actions_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["actions_position"]);
	}

	if ($tile->getBorderSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setBorderSizeType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT border_size_type FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["border_size_type"]);
	}

	if ($tile->getEnableRating() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setEnableRating(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT enable_rating FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["enable_rating"]);
	}

	if ($tile->getFontColorType() === \srag\Plugins\SrTile\Tile\Tile::COLOR_TYPE_PARENT) {
		$tile->setFontColorType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT font_color_type FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["font_color_type"]);
	}

	if ($tile->getFontSizeType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setFontSizeType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT font_size_type FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["font_size_type"]);
	}

	if ($tile->getImagePosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setImagePosition(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT image_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["image_position"]);
	}

	if ($tile->getLabelHorizontalAlign() === \srag\Plugins\SrTile\Tile\Tile::HORIZONTAL_ALIGN_PARENT) {
		$tile->setLabelHorizontalAlign(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT label_horizontal_align FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["label_horizontal_align"]);
	}

	if ($tile->getLabelVerticalAlign() === \srag\Plugins\SrTile\Tile\Tile::VERTICAL_ALIGN_PARENT) {
		$tile->setLabelVerticalAlign(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT label_vertical_align FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["label_vertical_align"]);
	}

	if ($tile->getLearningProgressPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setLearningProgressPosition(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT learning_progress_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["learning_progress_position"]);
	}

	if ($tile->getMarginType() === \srag\Plugins\SrTile\Tile\Tile::SIZE_TYPE_PARENT) {
		$tile->setMarginType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT margin_type FROM " . srag\Plugins\SrTile\Tile\Tile::class
			. " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["margin_type"]);
	}

	if ($tile->getObjectIconPosition() === \srag\Plugins\SrTile\Tile\Tile::POSITION_PARENT) {
		$tile->setObjectIconPosition(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT object_icon_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["object_icon_position"]);
	}

	if ($tile->getOpenObjWithOneChildDirect() === \srag\Plugins\SrTile\Tile\Tile::OPEN_PARENT) {
		$tile->setOpenObjWithOneChildDirect(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT open_obj_with_one_child_direct FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["open_obj_with_one_child_direct"]);
	}

	if ($tile->getRecommendMailTemplateType() === \srag\Plugins\SrTile\Tile\Tile::MAIL_TEMPLATE_PARENT) {
		$tile->setRecommendMailTemplateType(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT recommend_mail_template_type FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["recommend_mail_template_type"]);
	}

	if ($tile->getShowActions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->show_actions(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT actions_position FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_actions"]);
	}

	if ($tile->getShowDownloadCertificate() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowDownloadCertificate(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_download_certificate FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_download_certificate"]);
	}

	if ($tile->getShowFavoritesIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowFavoritesIcon(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_favorites_icon FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_favorites_icon"]);
	}

	if ($tile->getShowImageAsBackground() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowImageAsBackground(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_image_as_background FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_image_as_background"]);
	}

	if ($tile->getShowLearningProgress() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLearningProgress(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_learning_progress FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_learning_progress"]);
	}

	if ($tile->getShowLearningProgressFilter() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLearningProgressFilter(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_learning_progress_filter FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_learning_progress_filter"]);
	}

	if ($tile->getShowLearningProgressLegend() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLearningProgressLegend(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_learning_progress_legend FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_learning_progress_legend"]);
	}

	if ($tile->getShowLikesCount() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowLikesCount(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_likes_count FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_likes_count"]);
	}

	if ($tile->getShowObjectTabs() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowObjectTabs(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_object_tabs FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_object_tabs"]);
	}

	if ($tile->getShowPreconditions() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowPreconditions(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_preconditions FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_preconditions"]);
	}

	if ($tile->getShowRecommendIcon() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowRecommendIcon(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_recommend_icon FROM "
			. srag\Plugins\SrTile\Tile\Tile::class . " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_recommend_icon"]);
	}

	if ($tile->getShowTitle() === \srag\Plugins\SrTile\Tile\Tile::SHOW_PARENT) {
		$tile->setShowTitle(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT show_title FROM " . srag\Plugins\SrTile\Tile\Tile::class
			. " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["show_title"]);
	}

	if ($tile->getView() === \srag\Plugins\SrTile\Tile\Tile::VIEW_PARENT) {
		$tile->setView(\srag\DIC\SrTile\DICStatic::dic()->database()->queryF("SELECT view FROM " . srag\Plugins\SrTile\Tile\Tile::class
			. " WHERE ob_ref_id=%s", [ "integer" ], [
			\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getParentTile($tile)->getObjRefId()
		])->fetchAssoc()["view"]);
	}

	$tile->store();
}
?>
