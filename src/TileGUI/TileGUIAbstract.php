<?php

namespace srag\Plugins\SrTile\TileGUI;

use ilAdvancedSelectionListGUI;
use ilObjCategoryGUI;
use ilRepositoryGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 *
 */
abstract class TileGUIAbstract implements TileGUIInterface {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var Tile
	 */
	protected $tile;


	/**
	 * TileContainerGUI constructor
	 *
	 * @param Tile $tile
	 */
	public function __construct(Tile $tile) {
		$this->tile = $tile;
	}


	/**
	 * @inheritdoc
	 */
	public function render(): string {
		$this->setCardColor();

		$tpl = self::plugin()->template("Tile/tpl.tile.html");
		$tpl->setCurrentBlock("tile");
		$tpl->setVariable("TILE_ID", $this->tile->getTileId());
		$tpl->setVariable("LABEL", is_object($this->tile->returnIlObject()) ? $this->tile->returnIlObject()->getTitle() : "");
		$tpl->setVariable("COLOR", $this->tile->getLevelColor());
		$tpl->setVariable("LINK", $this->tile->returnLink());
		$tpl->setVariable("IMAGE", "./" . ILIAS_WEB_DIR . '/' . CLIENT_ID . '/' . $this->tile->returnRelativeImagePath(true));

		$tpl->setVariable("ACTIONS", $this->getActions());
		$tpl->parseCurrentBlock();

		return $tpl->get();
	}


	/**
	 * @inheritdoc
	 */
	public function getActions(): string {
		$advanced_selection_list = new ilAdvancedSelectionListGUI();
		$advanced_selection_list->setAsynch(true);
		$advanced_selection_list->setId('act_' . $this->tile->getObjRefId() . '_tile_' . $this->tile->getTileId());
		$advanced_selection_list->setAsynchUrl($this->getActionAsyncUrl());

		return $advanced_selection_list->getHTML(false);
	}


	/**
	 * @inheritdoc
	 */
	public function getActionAsyncUrl(): string {
		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "cmdrefid", $this->tile->getObjRefId());
		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "cmdmode", 'asynch');
		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "ref_id", $this->tile->getObjRefId());

		$async_url = self::dic()->ctrl()->getLinkTargetByClass(array(
			ilRepositoryGUI::class,
			ilObjCategoryGUI::class
		), "getAsynchItemList", "", false, false);

		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "cmdrefid", '');
		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "cmdmode", '');
		self::dic()->ctrl()->setParameterByClass(ilObjCategoryGUI::class, "ref_id", '');

		return $async_url;
	}


	/**
	 * @inheritdoc
	 */
	public function setCardColor()/*: void*/ {
		if (strlen($this->tile->getLevelColor()) > 0) {
			$css = ' #sr-tile-_';
			$css .= $this->tile->getTileId();
			$css .= '{ background-color: ' . $this->tile->getLevelColor() . '} ';
			self::dic()->mainTemplate()->addInlineCss($css);
		}
	}
}
