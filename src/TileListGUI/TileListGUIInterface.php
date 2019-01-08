<?php

namespace srag\Plugins\SrTile\TileListGUI;

/**
 * Interface TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
interface TileListGUIInterface {

	/**
	 * @var string
	 *
	 * @abstract
	 */
	const GUI_CLASS = "";
	/**
	 * @var string
	 *
	 * @abstract
	 */
	const LIST_CLASS = "";


	/**
	 * @return string
	 */
	public function render(): string;


	/**
	 *
	 */
	public function hideOriginalRowsOfTiles() /*:void*/
	;
}
