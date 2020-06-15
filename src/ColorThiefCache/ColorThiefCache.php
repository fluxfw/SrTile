<?php

namespace srag\Plugins\SrTile\ColorThiefCache;

use ActiveRecord;
use arConnector;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ColorThiefCache
 *
 * @package srag\Plugins\SrTile\ColorThiefCache
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ColorThiefCache extends ActiveRecord
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const TABLE_NAME = "ui_uihk_" . ilSrTilePlugin::PLUGIN_ID . "_c_t_c";
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $color = "";
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       255
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected $image_path = "";


    /**
     * ColorThiefCache constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/
        $primary_key_value = 0,
        arConnector $connector = null
    ) {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @return string
     */
    public function getColor() : string
    {
        return $this->color;
    }


    /**
     * @param string $color
     */
    public function setColor(string $color)/*: void*/
    {
        $this->color = $color;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     * @return string
     */
    public function getImagePath() : string
    {
        return $this->image_path;
    }


    /**
     * @param string $image_path
     */
    public function setImagePath(string $image_path)/*: void*/
    {
        $this->image_path = $image_path;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}
