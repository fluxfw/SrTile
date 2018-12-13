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
	 * @var bool
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      1
	 * @con_is_notnull  true
	 */
	protected $tile_enabled_children = false;
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
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $level_color_font = "";
	/**
	 * @var ilObject|null
	 */
	protected $object = NULL;


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
			case "tile_enabled_children":
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
			case "tile_enabled_children":
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
		if ($this->tile_enabled) {
			return true;
		}

		$parent_tile = self::tiles()->getParentTile($this);

		if ($parent_tile !== NULL) {
			return $parent_tile->isTileEnabledChildren();
		} else {
			return false;
		}
	}


	/**
	 * @param bool $tile_enabled
	 */
	public function setTileEnabled(bool $tile_enabled) {
		$this->tile_enabled = $tile_enabled;
	}


	/**
	 * @return bool
	 */
	public function isTileEnabledChildren(): bool {
		return $this->tile_enabled_children;
	}


	/**
	 * @param bool $tile_enabled_children
	 */
	public function setTileEnabledChildren(bool $tile_enabled_children) {
		$this->tile_enabled_children = $tile_enabled_children;
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
	public function getLevelColorFont(): string {
		return $this->level_color_font;
	}


	/**
	 * @param string $level_color_font
	 */
	public function setLevelColorFont(string $level_color_font) {
		$this->level_color_font = $level_color_font;
	}


	/**
	 * @param bool $invert
	 * @param bool $border
	 *
	 * @return string
	 */
	public function getColor(bool $invert = false,$border=false): string {
		$parent_tile = self::tiles()->getParentTile($this);

		$css = "";

		$background_color = $this->getLevelColor();
		if (empty($background_color) && $parent_tile !== NULL) {
			$background_color = $parent_tile->getLevelColor();
		}

		$font_color = $this->getLevelColorFont();
		if (empty($font_color) && !empty($this->getLevelColor())) {
			$font_color = $this->getContrastYIQ($this->getLevelColor());
		}
		if (empty($font_color) && $parent_tile !== NULL) {
			$font_color = $parent_tile->getLevelColorFont();
		}
		if (empty($font_color) && !empty($parent_tile->getLevelColor())) {
			$font_color = $this->getContrastYIQ($parent_tile->getLevelColor());
		}

		if ($invert) {
			if (!empty($background_color)) {
				$css .= 'color:#' . $background_color . '!important;';
			}

			if (!empty($font_color)) {
				$css .= 'background-color:#' . $font_color . '!important;';

				if($border) {
					$css .= 'border-color:#' . $font_color . '!important;';
				}
			}
		} else {
			if (!empty($background_color)) {
				$css .= 'background-color:#' . $background_color . '!important;';

				if($border) {
					$css .= 'border-color:#' . $background_color . '!important;';
				}
			}

			if (!empty($font_color)) {
				$css .= 'color:#' . $font_color . '!important;';
			}
		}

		return $css;
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

		return self::plugin()->directory() . "/templates/images/default_image.png";
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
		if ($this->object === NULL) {
			$this->object = ilObjectFactory::getInstanceByRefId($this->getObjRefId(), false);

			if ($this->object === false) {
				$this->object = NULL;
			}
		}

		return $this->object;
	}


	/**
	 * @return string
	 */
	public function returnLink(): string {
		return ilLink::_getStaticLink($this->getObjRefId());
	}


	/**
	 * https://24ways.org/2010/calculating-color-contrast/
	 *
	 * @param string $hexcolor
	 *
	 * @return string
	 */
	private function getContrastYIQ(string $hexcolor): string {
		$r = hexdec(substr($hexcolor, 0, 2));
		$g = hexdec(substr($hexcolor, 2, 2));
		$b = hexdec(substr($hexcolor, 4, 2));

		$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		return ($yiq >= 128) ? '000000' : 'FFFFFF';
	}
}
