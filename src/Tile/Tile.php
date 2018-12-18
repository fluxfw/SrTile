<?php

namespace srag\Plugins\SrTile\Tile;

use ActiveRecord;
use arConnector;
use ilLink;
use ilObject;
use ilObjectFactory;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
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
	const COLOR_TYPE_SET = 1;
	const COLOR_TYPE_CONTRAST = 2;
	const COLOR_TYPE_PARENT = 3;
	const FONT_SIZE_TYPE_SET = 1;
	const FONT_SIZE_TYPE_PARENT = 2;
	const MARGIN_TYPE_SET = 1;
	const MARGIN_TYPE_PARENT = 2;
	const POSITION_TOP = 1;
	const POSITION_BOTTOM = 2;
	const POSITION_LEFT = 3;
	const POSITION_RIGHT = 4;
	const POSITION_LEFT_TOP = 5;
	const POSITION_LEFT_BOTTOM = 6;
	const POSITION_RIGHT_TOP = 7;
	const POSITION_RIGHT_BOTTOM = 8;
	const POSITION_NONE = 9;
	const POSITION_PARENT = 10;
	const HORIZONTAL_ALIGN_LEFT = 1;
	const HORIZONTAL_ALIGN_CENTER = 2;
	const HORIZONTAL_ALIGN_RIGHT = 3;
	const HORIZONTAL_ALIGN_PARENT = 4;
	const VERTICAL_ALIGN_TOP = 1;
	const VERTICAL_ALIGN_CENTER = 2;
	const VERTICAL_ALIGN_BOTTOM = 3;
	const VERTICAL_ALIGN_PARENT = 4;
	const DEFAULT_ACTIONS_POSITION = self::POSITION_RIGHT;
	const DEFAULT_ACTIONS_VERTICAL_ALIGN = self::VERTICAL_ALIGN_BOTTOM;
	const DEFAULT_BACKGROUND_COLOR_TYPE = self::COLOR_TYPE_PARENT;
	const DEFAULT_BACKGROUND_COLOR = "";
	const DEFAULT_FONT_COLOR_TYPE = self::COLOR_TYPE_PARENT;
	const DEFAULT_FONT_COLOR = "";
	const DEFAULT_FONT_SIZE_TYPE = self::FONT_SIZE_TYPE_PARENT;
	const DEFAULT_FONT_SIZE = 16;
	const DEFAULT_IMAGE = "";
	const DEFAULT_IMAGE_POSITION = self::POSITION_TOP;
	const DEFAULT_LABEL_HORIZONTAL_ALIGN = self::HORIZONTAL_ALIGN_LEFT;
	const DEFAULT_LABEL_VERTICAL_ALIGN = self::VERTICAL_ALIGN_TOP;
	const DEFAULT_MARGIN_TYPE = self::MARGIN_TYPE_PARENT;
	const DEFAULT_MARGIN = 10;
	const DEFAULT_OBJECT_ICON_POSITION = Tile::POSITION_LEFT_BOTTOM;
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
	protected $image = self::DEFAULT_IMAGE;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $background_color_type = self::DEFAULT_BACKGROUND_COLOR_TYPE;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $background_color = self::DEFAULT_BACKGROUND_COLOR;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $font_color_type = self::DEFAULT_FONT_COLOR_TYPE;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $font_color = self::DEFAULT_FONT_COLOR;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $margin_type = self::DEFAULT_MARGIN_TYPE;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $margin = self::DEFAULT_MARGIN;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $font_size_type = self::DEFAULT_FONT_SIZE_TYPE;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $font_size = self::DEFAULT_FONT_SIZE;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $image_position = self::POSITION_PARENT;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $label_horizontal_align = self::HORIZONTAL_ALIGN_PARENT;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $label_vertical_align = self::VERTICAL_ALIGN_PARENT;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $actions_position = self::POSITION_PARENT;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $actions_vertical_align = self::VERTICAL_ALIGN_PARENT;
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_is_notnull  true
	 */
	protected $object_icon_position = self::POSITION_PARENT;
	/**
	 * @var ilObject|null
	 */
	protected $il_object = NULL;
	/**
	 * @var TileProperties|null
	 */
	protected $properties = NULL;


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
			case "actions_position":
			case "actions_vertical_align":
			case "background_color_type":
			case "font_color_type":
			case "font_size":
			case "font_size_type":
			case "image_position":
			case "label_horizontal_align":
			case "label_vertical_align":
			case "margin":
			case "margin_type":
			case "object_icon_position":
			case "obj_ref_id":
			case "tile_id":
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
	 *
	 * @internal
	 */
	public function getTileId(): int {
		return $this->tile_id;
	}


	/**
	 * @param int $tile_id
	 *
	 * @internal
	 */
	public function setTileId(int $tile_id)/*: void*/ {
		$this->tile_id = $tile_id;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getObjRefId() {
		return $this->obj_ref_id;
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @internal
	 */
	public function setObjRefId(int $obj_ref_id)/*: void*/ {
		$this->obj_ref_id = $obj_ref_id;
	}


	/**
	 * @return bool
	 *
	 * @internal
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
	 *
	 * @internal
	 */
	public function setTileEnabled(bool $tile_enabled)/*: void*/ {
		$this->tile_enabled = $tile_enabled;
	}


	/**
	 * @return bool
	 *
	 * @internal
	 */
	public function isTileEnabledChildren(): bool {
		return $this->tile_enabled_children;
	}


	/**
	 * @param bool $tile_enabled_children
	 *
	 * @internal
	 */
	public function setTileEnabledChildren(bool $tile_enabled_children)/*: void*/ {
		$this->tile_enabled_children = $tile_enabled_children;
	}


	/**
	 * @return string
	 *
	 * @internal
	 *
	 */
	public function getImage(): string {
		return $this->image;
	}


	/**
	 * @param string $image
	 *
	 * @internal
	 */
	public function setImage(string $image)/*: void*/ {
		$this->image = $image;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getBackgroundColorType(): int {
		return $this->background_color_type;
	}


	/**
	 * @param int $background_color_type
	 *
	 * @internal
	 */
	public function setBackgroundColorType(int $background_color_type)/*: void*/ {
		$this->background_color_type = $background_color_type;
	}


	/**
	 * @return string
	 *
	 * @internal
	 */
	public function getBackgroundColor(): string {
		return $this->background_color;
	}


	/**
	 * @param string $background_color
	 *
	 * @internal
	 */
	public function setBackgroundColor(string $background_color)/*: void*/ {
		$this->background_color = $background_color;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getFontColorType(): int {
		return $this->font_color_type;
	}


	/**
	 * @param int $font_color_type
	 *
	 * @internal
	 */
	public function setFontColorType(int $font_color_type)/*: void*/ {
		$this->font_color_type = $font_color_type;
	}


	/**
	 * @return string
	 *
	 * @internal
	 */
	public function getFontColor(): string {
		return $this->font_color;
	}


	/**
	 * @param string $font_color
	 *
	 * @internal
	 */
	public function setFontColor(string $font_color)/*: void*/ {
		$this->font_color = $font_color;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getMarginType(): int {
		return $this->margin_type;
	}


	/**
	 * @param int $margin_type
	 *
	 * @internal
	 */
	public function setMarginType(int $margin_type)/*: void*/ {
		$this->margin_type = $margin_type;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getMargin(): int {
		return $this->margin;
	}


	/**
	 * @param int $margin
	 *
	 * @internal
	 */
	public function setMargin(int $margin)/*: void*/ {
		$this->margin = $margin;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getFontSizeType(): int {
		return $this->font_size_type;
	}


	/**
	 * @param int $font_size_type
	 *
	 * @internal
	 */
	public function setFontSizeType(int $font_size_type)/*: void*/ {
		$this->font_size_type = $font_size_type;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getFontSize(): int {
		return $this->font_size;
	}


	/**
	 * @param int $font_size
	 *
	 * @internal
	 */
	public function setFontSize(int $font_size)/*: void*/ {
		$this->font_size = $font_size;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getImagePosition(): int {
		return $this->image_position;
	}


	/**
	 * @param int $image_position
	 *
	 * @internal
	 */
	public function setImagePosition(int $image_position)/*: void*/ {
		$this->image_position = $image_position;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getLabelHorizontalAlign(): int {
		return $this->label_horizontal_align;
	}


	/**
	 * @param int $label_horizontal_align
	 *
	 * @internal
	 */
	public function setLabelHorizontalAlign(int $label_horizontal_align)/*: void*/ {
		$this->label_horizontal_align = $label_horizontal_align;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getLabelVerticalAlign(): int {
		return $this->label_vertical_align;
	}


	/**
	 * @param int $label_vertical_align
	 *
	 * @internal
	 */
	public function setLabelVerticalAlign(int $label_vertical_align)/*: void*/ {
		$this->label_vertical_align = $label_vertical_align;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getActionsPosition(): int {
		return $this->actions_position;
	}


	/**
	 * @param int $actions_position
	 *
	 * @internal
	 */
	public function setActionsPosition(int $actions_position)/*: void*/ {
		$this->actions_position = $actions_position;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getActionsVerticalAlign(): int {
		return $this->actions_vertical_align;
	}


	/**
	 * @param int $actions_vertical_align
	 *
	 * @internal
	 */
	public function setActionsVerticalAlign(int $actions_vertical_align)/*: void*/ {
		$this->actions_vertical_align = $actions_vertical_align;
	}


	/**
	 * @return int
	 *
	 * @internal
	 */
	public function getObjectIconPosition(): int {
		return $this->object_icon_position;
	}


	/**
	 * @param int $object_icon_position
	 *
	 * @internal
	 */
	public function setObjectIconPosition(int $object_icon_position)/*: void*/ {
		$this->object_icon_position = $object_icon_position;
	}


	/**
	 * @return TileProperties
	 */
	public function getProperties(): TileProperties {
		if ($this->properties === NULL) {
			$this->properties = new TileProperties($this);
		}

		return $this->properties;
	}


	/**
	 * Return the path to the icon
	 *
	 * @param bool $append_filename If true, append filename of image
	 *
	 * @return string
	 */
	public function returnRelativeImagePath(bool $append_filename = false): string {
		$path = ilSrTilePlugin::WEB_DATA_FOLDER . "/" . "tile_" . $this->getTileId() . "/";
		if ($append_filename) {
			if (!empty($this->getImage())) {
				$path .= $this->getImage();
			}
		}

		return $path;
	}


	/**
	 * @return ilObject|null
	 */
	public function getIlObject()/*: ?ilObject*/ {
		if ($this->il_object === NULL) {
			$this->il_object = ilObjectFactory::getInstanceByRefId($this->getObjRefId(), false);

			if ($this->il_object === false) {
				$this->il_object = NULL;
			}
		}

		return $this->il_object;
	}


	/**
	 * @return string
	 */
	public function returnLink(): string {
		return ilLink::_getStaticLink($this->getObjRefId());
	}
}
