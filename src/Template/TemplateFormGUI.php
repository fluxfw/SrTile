<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTileConfigGUI;
use srag\Plugins\SrTile\Tile\TileFormGUI;

/**
 * Class TemplateFormGUI
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TemplateFormGUI extends TileFormGUI {

	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(ilSrTileConfigGUI::CMD_UPDATE_TEMPLATE, $this->txt("submit"), "tile_submit");

		$this->addCommandButton($this->parent->getCmdForTab(ilSrTileConfigGUI::TAB_TEMPLATES), $this->txt("cancel"));
	}
}
