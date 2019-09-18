<?php

namespace srag\Plugins\SrTile\TileListGUI\TileListStaticGUI;

use srag\Plugins\SrTile\TileGUI\TileStaticGUI\TileStaticGUI;
use srag\Plugins\SrTile\TileList\TileListStatic\TileListStatic;
use srag\Plugins\SrTile\TileListGUI\TileListGUIAbstract;

/**
 * Class TileListStaticGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI\TileListStaticGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListStaticGUI extends TileListGUIAbstract
{

    const GUI_CLASS = TileStaticGUI::class;
    const LIST_CLASS = TileListStatic::class;
}
