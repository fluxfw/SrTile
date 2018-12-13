<?php

namespace srag\Plugins\SrTile\TileGUI\TileFormGUI;

use ilCheckboxInputGUI;
use ilColorPickerInputGUI;
use ilException;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use ilImageFileInputGUI;
use ilObject;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use SrTileGUI;

/**
 * Class TileFormGUI
 *
 * @package srag\Plugins\srTile\TileGUI\TileFormGUI
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
	 *
	 * @throws ilException
	 */
	public function __construct(SrTileGUI $parent, Tile $tile) {
		$this->tile = $tile;

		parent::__construct($parent);

		if (!self::access()->hasWriteAccess(srTileGUI::filterRefId())) {
			throw new ilException("You have no permission to access this page");
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			case 'tile_image':
				return "./" . ILIAS_WEB_DIR . '/' . CLIENT_ID . '/' . $this->tile->returnRelativeImagePath(true);

			default:
				if (method_exists($this->tile, $method = 'get' . $this->strToCamelCasE($key))) {
					return $this->tile->{$method}($key);
				}
				if (method_exists($this->tile, $method = 'is' . $this->strToCamelCasE($key))) {
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
		$this->addCommandButton(SrTileGUI::CMD_UPDATE_TILE, $this->txt("submit"), "tile_submit");

		$this->addCommandButton(SrTileGUI::CMD_CANCEL, $this->txt("cancel"), "tile_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"tile_enabled" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					"tile_image" => [
						self::PROPERTY_CLASS => ilImageFileInputGUI::class,
						self::PROPERTY_REQUIRED => false
					],
					"level_color" => [
						self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
						self::PROPERTY_REQUIRED => false,
						'setDefaultColor' => ''
					],
					"level_color_font" => [
						self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
						self::PROPERTY_REQUIRED => false,
						'setDefaultColor' => ''
					]
				]
			],
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
		$this->setTitle($this->txt("tile") . ": " . ilObject::_lookupTitle(ilObject::_lookupObjectId(srTileGUI::filterRefId())));
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {

		if ($this->tile->getTileId() == 0) {
			//Store a new Object to get an Id for later.
			$this->tile->store();
		}

		switch ($key) {
			case 'tile_image':
				if (!self::dic()->upload()->hasBeenProcessed()) {
					self::dic()->upload()->process();
				}

				/** @var UploadResult $result */
				$result = array_pop(self::dic()->upload()->getResults());

				if ($this->getInput('tile_image_delete') || $result->getSize() > 0) {
					$image_path = ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->tile->returnRelativeImagePath(true);
					if (file_exists($image_path)) {
						unlink($image_path);
					}
					$this->tile->setTileImage('');
				}

				if (intval($result->getSize()) === 0) {
					break;
				}

				$file_name = $this->tile->getTileId() . "." . pathinfo($result->getName(), PATHINFO_EXTENSION);

				self::dic()->upload()->moveOneFileTo($result, $this->tile->returnRelativeImagePath(), Location::WEB, $file_name, true);

				$this->tile->setTileImage($file_name);
				break;

			default:
				if (method_exists($this->tile, $method = 'set' . $this->strToCamelCasE($key))) {
					$this->tile->{$method}($value);
				}
				break;
		}

		$this->tile->store();
	}


	/**
	 * @return Tile
	 */
	public function getTile(): Tile {
		return $this->tile;
	}


	/**
	 * @param string $string
	 *
	 * @return string
	 */
	protected function strToCamelCasE($string): string {
		return str_replace('_', '', ucwords($string, '_'));
	}
}
