<#1>
<?php
\srag\Plugins\SrTile\Tile\Tile::updateDB();

// Create default values for top tile
if (\srag\Plugins\SrTile\Tile\Tiles::getInstance()->getInstanceForObjRefId(ROOT_FOLDER_ID, false)->getTileId() === 0) {
	$tile = new \srag\Plugins\SrTile\Tile\Tile();
	$tile->setObjRefId(ROOT_FOLDER_ID);
	$tile->setTileEnabledChildren(true);
	$tile->setBackgroundColorType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_BACKGROUND_COLOR_TYPE);
	$tile->setFontColorType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_FONT_COLOR_TYPE);
	$tile->setMarginType(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_MARGIN_TYPE);
	$tile->setMargin(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_MARGIN);
	$tile->setFontSizeType(\srag\Plugins\SrTile\Tile\Tile::MARGIN_TYPE_SET);
	$tile->setFontSize(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_FONT_SIZE);
	$tile->setImagePosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_IMAGE_POSITION);
	$tile->setLabelHorizontalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_LABEL_HORIZONTAL_ALIGN);
	$tile->setLabelVerticalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_LABEL_VERTICAL_ALIGN);
	$tile->setActionsPosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_ACTIONS_POSITION);
	$tile->setActionsVerticalAlign(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_ACTIONS_VERTICAL_ALIGN);
	$tile->setObjectIconPosition(\srag\Plugins\SrTile\Tile\Tile::DEFAULT_OBJECT_ICON_POSITION);
	$tile->store();
}
?>
