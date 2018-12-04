<?php

namespace srag\Plugins\SrTile\Tile;

use ilFileSystemStorage;
use ILIAS\Filesystem\Filesystem;
use ILIAS\FileUpload\Location;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\DI\Container;
use ilImageFileInputGUI;
use ilColorPickerInputGUI;
use SrTileGUI;
use ilSrTilePlugin;
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
			case 'tile_image':
				return "./" . ILIAS_WEB_DIR . '/' . CLIENT_ID . '/' . $this->tile->returnRelativeImagePath(true);
				break;
			default:
				if (method_exists($this->tile, $method = 'get' . $this->strToCamelCasE($key))) {
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
		$this->addCommandButton(SrTileGUI::CMD_UPDATE_TILE, self::plugin()->translate("submit", SrTileGUI::LANG_MODULE_TILE), "tile_submit");

		$this->addCommandButton("", self::plugin()->translate("cancel", SrTileGUI::LANG_MODULE_TILE), "tile_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"tile_image" => [
				self::PROPERTY_CLASS => ilImageFileInputGUI::class,
				self::PROPERTY_REQUIRED => false
			],
			"level_color" => [
				self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				'setDefaultColor' => '#ffffff'
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
		$this->setTitle(self::plugin()->translate("tile", SrTileGUI::LANG_MODULE_TILE));
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case 'tile_image':
				if (!self::dic()->upload()->hasBeenProcessed()) {
					self::dic()->upload()->process();
				}
				/** @var UploadResult $result */
				$result = array_pop(self::dic()->upload()->getResults());
				if ($result->getSize() == 0) {
					break;
				}
				$file_name = $this->tile->getTileId() . "." . pathinfo($result->getName(), PATHINFO_EXTENSION);
				self::dic()->upload()->moveOneFileTo($result, $this->tile->returnRelativeImagePath(), Location::WEB, $file_name, true);
				$this->tile->setTileImage($file_name);
				break;
			default:
				if (method_exists($this->tile, $method = 'set' . $this->strToCamelCasE($key))) {
					$this->tile->{$method}($this->getInput($key));
				}
				break;
		}

		if($this->getInput('tile_image_delete')) {
			$this->tile->setTileImage('');
		}

		$this->tile->store();
	}


	/**
	 * @inheritdoc
	 */
	public function updateTile()/*: void*/ {
		exit;
	}


	/**
	 * @return Tile
	 */
	public function getTile(): Tile {
		return $this->tile;
	}


	/**
	 * @param $string
	 *
	 * @return string
	 */
	protected function strToCamelCasE($string): string {
		return str_replace('_', '', ucwords($string, '_'));
	}
}
