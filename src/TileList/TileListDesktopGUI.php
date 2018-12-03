<?php
namespace srag\Plugins\SrTile\TileList;

use srag\Plugins\SrTile\Utils\SrTileTrait;
use srag\DIC\SrTile\DICTrait;

use ilSrTilePlugin;

/**
 * Class TileListContainerGUI
 *
 * Generated by srag\PluginGenerator v0.9.2
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author            studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 *
 */
class TileListDesktopGUI extends TileListGUIAbstract {

	/**
	 * TileListDesktopGUIGUI constructor.
	 *
	 * @param int $usr_id
	 */
	public function __construct(int $usr_id) /*:void*/ {
		$this->tile_list = TileListDesktop::getInstance($usr_id);
	}

	/**
	 * @return void
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