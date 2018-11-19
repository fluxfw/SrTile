<?php

namespace srag\Plugins\SrTile\Tile;

use ilCheckboxInputGUI;
use ilHiddenInputGUI;
use ilImageFileInputGUI;
use SrTileGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileFormGUI
 *
 * @package srag\Plugins\srTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TileFormGUI extends PropertyFormGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const LANG_MODULE = SrTileGUI::LANG_MODULE_TILE;
	/**
	 * @var Tile|null
	 */
	protected $tile = NULL;


	/**
	 * TileFormGUI constructor
	 *
	 * @param SrTileGUI $parent
	 * @param Tile      $tile
	 */
	public function __construct(SrTileGUI $parent, Tile $tile) {
		$this->tile = $tile;

		parent::__construct($parent);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			case 'show_children_as_tile':
				return $this->tile->isShowChildrenAsTile();
			default:
				if (method_exists($this->tile, $method = 'get' . ucfirst($key))) {
					return $this->tile->{$method}($key);
				}
		}

		return NULL;
	}


	/**
	 * @inheritdoc
	 */
	protected function initAction()/*: void*/ {
		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(SrTileGUI::CMD_UPDATE_TILE, self::plugin()
			->translate("submit", SrTileGUI::LANG_MODULE_TILE), "tile_submit");

		$this->addCommandButton("", self::plugin()->translate("cancel", SrTileGUI::LANG_MODULE_TILE), "tile_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"show_children_as_tile" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
				self::PROPERTY_REQUIRED => true,
			],
			"tile_image" => [
				self::PROPERTY_CLASS => ilImageFileInputGUI::class,
				self::PROPERTY_REQUIRED => false
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("tile_form");
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
	}


	/**
	 * @inheritdoc
	 */
	protected function setValue(/*string*/
		$key, $value)/*: void*/ {
	}


	/**
	 * @inheritdoc
	 */
	public function updateTile()/*: void*/ {
		$show_children_as_tile = $this->getInput("show_children_as_tile");
		$this->tile->setShowChildrenAsTile("show_children_as_tile" ? 1 : 0);

		$tile_image = (array)$this->getInput('tile_image');
		if (count($tile_image) > 0 && strlen($tile_image['name']) > 0) {
			if (!is_dir($this->tile->returnImagePath())) {
				ilUtil::makeDirParents($this->tile->returnImagePath());
			}
			$this->tile->setTileImage($tile_image['name']);
		}
		$tile_image_name = $this->tile->getTileImage() ? $this->tile->getTileImage() : $tile_image['name'];
		ilUtil::moveUploadedFile($tile_image['tmp_name'], $tile_image_name, $this->tile->returnImagePath(true), false);


		$this->tile->save();
	}


	/**
	 * @return Tile
	 */
	public function getTile(): Tile {
		return $this->tile;
	}
}
