<?php

namespace srag\Plugins\SrTile\TileGUI;

use ilAdvancedSelectionListGUI;
use ilObject;
use ilObjRootFolderGUI;
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
		$tpl->setVariable("LABEL", ($this->tile->returnIlObject() !== NULL ? $this->tile->returnIlObject()->getTitle() : ""));
		$tpl->setVariable("LINK", $this->tile->returnLink());
		$tpl->setVariable("IMAGE", $this->tile->getImage());

		if (self::access()->hasWriteAccess($this->tile->getObjRefId())) {
			$tpl->setVariable("ACTIONS", $this->getActions());
		}

		$icon = ilObject::_getIcon(($this->tile->returnIlObject() !== NULL ? $this->tile->returnIlObject()->getId() : NULL), "small");
		if (file_exists($icon)) {
			$tpl->setVariable("ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));
		}

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
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "ref_id", ROOT_FOLDER_ID);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", $this->tile->getObjRefId());

		$async_url = self::dic()->ctrl()->getLinkTargetByClass(array(
			ilRepositoryGUI::class,
			ilObjRootFolderGUI::class
		), "getAsynchItemList", "", true, false);

		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "ref_id", NULL);
		self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", NULL);

		return $async_url;
	}


	/**
	 * @inheritdoc
	 */
	public function setCardColor()/*: void*/ {
		// TODO: Not work?!
		if (strlen($this->tile->getLevelColor()) > 0) {
			$id = "#sr-tile-_" . $this->tile->getTileId();

			$css = $id . '{' . $this->tile->getColor() . '}';
			self::dic()->mainTemplate()->addInlineCss($css);
		}
	}
}
