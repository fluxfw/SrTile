<?php

namespace srag\Plugins\SrTile\TileListGUI;

/**
 * Interface TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 *
 */
interface TileListGUIInterface {

	/**
	 * @return string
	 */
	public function render(): string;


	/**
	 *
	 * @return string
	 */
	public function getHtml(): string;


	/**
	 *
	 */
	public function hideOriginalRowsOfTiles() /*:void*/
	;
}
