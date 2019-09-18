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
    const TABLE_NAME = "ui_uihk_srtile_c_t_c";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
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
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $color = "";


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
     * @return string
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @param string $field_name
     *
     * @return mixed|null
     */
    public function sleep(/*string*/
        $field_name
    ) {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            default:
                return null;
        }
    }


    /**
     * @param string $field_name
     * @param mixed  $field_value
     *
     * @return mixed|null
     */
    public function wakeUp(/*string*/
        $field_name,
        $field_value
    ) {
        switch ($field_name) {
            default:
                return null;
        }
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
}
