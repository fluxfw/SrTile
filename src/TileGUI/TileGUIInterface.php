<?php

namespace srag\Plugins\SrTile\TileGUI;

/**
 * Interface TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 *
 */
interface TileGUIInterface {

	/**
	 * @return string
	 */
	public function render(): string;


	/**
	 * @return string
	 */
	public function getActions(): string;


	/**
	 * @return string
	 */
	public function getActionAsyncUrl(): string;


	/**
	 *
	 */
	public function setCardColor() /*:void*/
	;
}
