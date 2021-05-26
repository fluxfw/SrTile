<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTilePlugin;
use srag\Plugins\SrTile\Tile\Tile;

/**
 * Class Template
 *
 * @package srag\Plugins\SrTile\Template
 */
class Template extends Tile
{

    const IMAGE_PREFIX = "template_";
    const TABLE_NAME = "ui_uihk_" . ilSrTilePlugin::PLUGIN_ID . "_tmpl";
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
     * @inheritDoc
     */
    public function _getTitle() : string
    {
        if ($this->object_type !== Repository::TYPE_OTHER) {
            return self::dic()->language()->txt("obj_" . $this->object_type);
        } else {
            return self::plugin()->translate(substr($this->object_type, 1), TemplatesConfigGUI::LANG_MODULE);
        }
    }


    /**
     * @return string
     */
    public function getObjectType() : string
    {
        return $this->object_type;
    }


    /**
     * @param string $object_type
     */
    public function setObjectType(string $object_type)/*: void*/
    {
        $this->object_type = $object_type;
    }
}
