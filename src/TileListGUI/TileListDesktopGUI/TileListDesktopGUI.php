<?php

namespace srag\Plugins\SrTile\TileListGUI\TileListDesktopGUI;

use srag\Plugins\SrTile\Tile\TileDesktopGUI\TileDesktopGUI;
use srag\Plugins\SrTile\TileList\TileListDesktop\TileListDesktop;
use srag\Plugins\SrTile\TileListGUI\TileListGUIAbstract;

/**
 * Class TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI\TileListDesktopGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListDesktopGUI extends TileListGUIAbstract {

	const GUI_CLASS = TileDesktopGUI::class;
	const LIST_CLASS = TileListDesktop::class;


	/**
	 * @inheritdoc
	 */
	public function hideOriginalRowsOfTiles(bool $global_layout = false) /*:void*/ {
		parent::hideOriginalRowsOfTiles($global_layout);
	}
}
