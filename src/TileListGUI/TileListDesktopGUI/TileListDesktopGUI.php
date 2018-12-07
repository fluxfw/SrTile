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
 *
 */
class TileListDesktopGUI extends TileListGUIAbstract {

	/**
	 * TileListDesktopGUIGUI constructor
	 *
	 * @param int $usr_id
	 */
	public function __construct(int $usr_id) /*:void*/ {
		$this->tile_list = TileListDesktop::getInstance($usr_id);
	}


	/**
	 * @inheritdoc
	 */
	public function getHtml(): string {
		$tile_html = '';
		foreach ($this->tile_list->getTiles() as $tile) {
			if (strlen($tile->getTileImage()) > 0) {
				$tile_gui = new TileDesktopGUI($tile);
				$tile_html .= $tile_gui->render();
			}
		}

		return $tile_html;
	}


	/**
	 * @inheritdoc
	 */
	public function hideOriginalRowsOfTiles() /*:void*/ {
		$css = '';
		foreach ($this->tile_list->getTiles() as $tile) {
			$css .= ' #lg_div_';
			$css .= $tile->getObjRefId();
			$css .= '_pref_';
			$css .= 0;
			$css .= '{ display: none !important;} ';
		}

		self::dic()->mainTemplate()->addInlineCss($css);
	}
}
