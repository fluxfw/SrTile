<?php

namespace srag\Plugins\SrTile\Config;

use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use ilImageFileInputGUI;
use ilSrTilePlugin;
use srag\ActiveRecordConfig\SrTile\ActiveRecordConfigFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrTile\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const CONFIG_CLASS_NAME = Config::class;


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		$value = parent::getValue($key);

		switch ($key) {
			case Config::KEY_DEFAULT_IMAGE:
				if (!empty($value)) {
					$value = "./" . ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . ilSrTilePlugin::WEB_DATA_FOLDER . "/" . $value;
				} else {
					$value = "";
				}
				break;

			default:
				break;
		}

		return $value;
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			Config::KEY_DEFAULT_IMAGE => [
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_CLASS => ilImageFileInputGUI::class
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case Config::KEY_DEFAULT_IMAGE:
				if (!self::dic()->upload()->hasBeenProcessed()) {
					self::dic()->upload()->process();
				}

				/** @var UploadResult $result */
				$result = array_pop(self::dic()->upload()->getResults());

				if ($this->getInput(Config::KEY_DEFAULT_IMAGE . '_delete')||$result->getSize()>0) {
					$image_path = $this->getValue(Config::KEY_DEFAULT_IMAGE);
					if (file_exists($image_path)) {
						unlink($image_path);
					}
					$value = "";
				}

				if (intval($result->getSize()) === 0) {
					break;
				}

				$file_name = "default_image." . pathinfo($result->getName(), PATHINFO_EXTENSION);

				self::dic()->upload()->moveOneFileTo($result, ilSrTilePlugin::WEB_DATA_FOLDER, Location::WEB, $file_name, true);

				$value = $file_name;
				break;

			default:
				break;
		}

		parent::storeValue($key, $value);
	}
}
