<?php

namespace srag\Plugins\SrTile\Tile;

use ActiveRecord;
use arConnector;
use ilObjCategory;
use ilObjCourse;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use ilObject;
use ilLink;

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
	/**
	 * @var string
	 *
	 */
	const TABLE_NAME = "ui_uihk_srtile_tile";
	/**
	 * @var string
	 *
	 */
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const OBJ_TYPE_CAT = "cat";
	const OBJ_TYPE_CRS = "crs";
	/**
	 * @var self
	 */
	protected static $instances_by_ref_id = array();
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
	 * @return Tile|null
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
			case "show_children_as_tile":
				return ($field_value ? 1 : 0);
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

			case "show_children_as_tile":
				return boolval($field_value);

			default:
				return NULL;
		}
	}


	/**
	 * Return the path to the icon
	 *
	 * @param bool $append_filename If true, append filename of image
	 *
	 * @return string
	 */
	public function returnRelativeImagePath($append_filename = false) {

		$path = ilSrTilePlugin::WEB_DATA_FOLDER . '/' . 'tile_' . $this->getTileId() . '/';
		if ($append_filename) {
			if (strlen($this->getTileImage()) > 0) {
				$path .= $this->getTileImage();
			}
		}

		return $path;
	}


	/**
	 * @return ilObjCategory|ilObjCourse|null
	 */
	public function returnIlObject() {


		switch (ilObject::_lookupType($this->getObjRefId(), true)) {
			case 'cat':
				return new ilObjCategory($this->getObjRefId());
			case 'crs':
				return new ilObjCourse($this->getObjRefId());
			default:
				return NULL;
		}
	}


	public function returnLink() {
		return ilLink::_getStaticLink($this->getObjRefId(), ilObject::_lookupType($this->getObjRefId(), true));
	}


	public static function returnTileIdByRefId($obj_ref_id) {
		return self::where([ 'obj_ref_id' => $obj_ref_id ])->first()->getTileId();
	}
}
