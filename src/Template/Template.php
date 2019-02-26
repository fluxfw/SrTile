<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTileConfigGUI;
use srag\Plugins\SrTile\Tile\Tile;

/**
 * Class Template
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Template extends Tile {

	const TABLE_NAME = "ui_uihk_srtile_tmpl";
	const IMAGE_PREFIX = "template_";
	/**
	 * @var int
	 *
	 * @con_has_field   true
	 * @con_fieldtype   integer
	 * @con_length      8
	 * @con_is_notnull  true
	 */
	protected $obj_ref_id = ROOT_FOLDER_ID;
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   text
	 * @con_is_notnull  true
	 */
	protected $object_type = "";


	/**
	 * @return string
	 */
	public function getObjectType(): string {
		return $this->object_type;
	}


	/**
	 * @param string $object_type
	 */
	public function setObjectType(string $object_type)/*: void*/ {
		$this->object_type = $object_type;
	}


	/**
	 * @inheritdoc
	 */
	public function _getTitle(): string {
		if ($this->object_type !== Templates::TYPE_OTHER) {
			return self::dic()->language()->txt("obj_" . $this->object_type);
		} else {
			return self::plugin()->translate(substr($this->object_type, 1), ilSrTileConfigGUI::LANG_MODULE_TEMPLATE);
		}
	}
}
