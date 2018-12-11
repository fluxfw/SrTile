<?php

namespace srag\Plugins\SrTile\Tile;

use ActiveRecord;
use arConnector;
use ilLink;
use ilObject;
use ilObjectFactory;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Tile extends ActiveRecord {

	use DICTrait;
	use SrTileTrait;
	const TABLE_NAME = "ui_uihk_srtile_tile";
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const GRAY_IMAGE = "data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==";
	/**
	 * @var self[]
	 */
	protected static $instances_by_ref_id = [];
	/**
	 * @var int
	 *
	 * @con_has_field    true
	 * @con_fieldtype    integer
	 * @con_length       8
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 * @con_sequence     true
	 */
	protected $tile_id;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $obj_ref_id;
	/**
	 * @var bool
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      1
	 * @con_is_notnull  true
	 */
	protected $tile_enabled = false;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $tile_image = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $level_color = "";


	/**
	 * Tile constructor
	 *
	 * @param int              $primary_key_value
	 * @param arConnector|null $connector
	 */
	public function __construct(/*int*/
		$primary_key_value = 0, arConnector $connector = NULL) {
		parent::__construct($primary_key_value, $connector);
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return self|null
	 */
	public static function getInstanceForObjRefId(int $obj_ref_id) /*:?self*/ {
		if (self::$instances_by_ref_id[$obj_ref_id] === NULL) {
			if (self::$instances_by_ref_id[$obj_ref_id] = self::where([ 'obj_ref_id' => $obj_ref_id ])->first()) {
				return self::$instances_by_ref_id[$obj_ref_id];
			};

			return NULL;
		}

		return self::$instances_by_ref_id[$obj_ref_id];
	}


	/**
	 * @return string
	 */
	public function getConnectorContainerName(): string {
		return self::TABLE_NAME;
	}


	/**
	 * @param string $field_name
	 *
	 * @return mixed|null
	 */
	public function sleep(/*string*/
		$field_name) {
		$field_value = $this->{$field_name};

		switch ($field_name) {
			case "tile_enabled":
				return ($field_value ? 1 : 0);
			default:
				return NULL;
		}
	}


	/**
	 * @param string $field_name
	 * @param mixed  $field_value
	 *
	 * @return mixed|null
	 */
	public function wakeUp(/*string*/
		$field_name, $field_value) {
		switch ($field_name) {
			case "tile_id":
			case "obj_ref_id":
				return intval($field_value);

			case "tile_enabled":
				return boolval($field_value);

			default:
				return NULL;
		}
	}


	/**
	 * @return int
	 */
	public function getTileId(): int {
		return $this->tile_id;
	}


	/**
	 * @param int $tile_id
	 */
	public function setTileId(int $tile_id) {
		$this->tile_id = $tile_id;
	}


	/**
	 * @return int
	 */
	public function getObjRefId() {
		return $this->obj_ref_id;
	}


	/**
	 * @param int $obj_ref_id
	 */
	public function setObjRefId(int $obj_ref_id) {
		$this->obj_ref_id = $obj_ref_id;
	}


	/**
	 * @return bool
	 */
	public function isTileEnabled(): bool {
		return $this->tile_enabled;
	}


	/**
	 * @param bool $tile_enabled
	 */
	public function setTileEnabled(bool $tile_enabled) {
		$this->tile_enabled = $tile_enabled;
	}


	/**
	 * @return string
	 *
	 */
	public function getTileImage(): string {
		return $this->tile_image;
	}


	/**
	 * @param string $tile_image
	 */
	public function setTileImage(string $tile_image) {
		$this->tile_image = $tile_image;
	}


	/**
	 * @return string
	 */
	public function getLevelColor(): string {
		return $this->level_color;
	}


	/**
	 * @param string $level_color
	 */
	public function setLevelColor(string $level_color) {
		$this->level_color = $level_color;
	}


	/**
	 * @return string
	 */
	public function getImage(): string {
		if (!empty($this->getTileImage())) {
			$image_path = ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->returnRelativeImagePath(true);
			if (file_exists($image_path)) {
				return "./" . $image_path;
			}
		}

		$default_image = Config::getField(Config::KEY_DEFAULT_IMAGE);
		if (!empty($default_image)) {
			$image_path = ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . ilSrTilePlugin::WEB_DATA_FOLDER . "/" . $default_image;
			if (file_exists($image_path)) {
				return "./" . $image_path;
			}
		}

		return self::GRAY_IMAGE;
	}


	/**
	 * Return the path to the icon
	 *
	 * @param bool $append_filename If true, append filename of image
	 *
	 * @return string
	 */
	public function returnRelativeImagePath(bool $append_filename = false): string {
		$path = ilSrTilePlugin::WEB_DATA_FOLDER . '/' . 'tile_' . $this->getTileId() . '/';
		if ($append_filename) {
			if (strlen($this->getTileImage()) > 0) {
				$path .= $this->getTileImage();
			}
		}

		return $path;
	}


	/**
	 * @return ilObject|null
	 */
	public function returnIlObject()/*: ?ilObject*/ {
		$object = ilObjectFactory::getInstanceByRefId($this->getObjRefId(), false);

		if ($object !== false) {
			return $object;
		} else {
			return NULL;
		}
	}


	/**
	 * @return string
	 */
	public function returnLink(): string {
		return ilLink::_getStaticLink($this->getObjRefId(), ilObject::_lookupType($this->getObjRefId(), true));
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return int
	 */
	public static function returnTileIdByRefId(int $obj_ref_id): int {
		return self::where([ 'obj_ref_id' => $obj_ref_id ])->first()->getTileId();
	}
}
