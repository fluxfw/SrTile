<?php

namespace srag\Plugins\SrTile\TileListGUI\TileListContainerGUI;

use srag\Plugins\SrTile\TileGUI\TileContainerGUI\TileContainerGUI;
use srag\Plugins\SrTile\TileList\TileListContainer\TileListContainer;
use srag\Plugins\SrTile\TileListGUI\TileListGUIAbstract;

/**
 * Class TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI\TileListContainerGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListContainerGUI extends TileListGUIAbstract {

	const GUI_CLASS = TileContainerGUI::class;
	const LIST_CLASS = TileListContainer::class;
}
