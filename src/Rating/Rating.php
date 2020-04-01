<?php

namespace srag\Plugins\SrTile\Rating;

use ActiveRecord;
use arConnector;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Rating
 *
 * @package srag\Plugins\SrTile\Rating
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Rating extends ActiveRecord
{

    use DICTrait;
    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_" . ilSrTilePlugin::PLUGIN_ID . "_rating";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
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
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $rating_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $obj_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $user_id;


    /**
     * Rating constructor
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
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            default:
                return null;
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "obj_id":
            case "rating_id":
            case "user_id":
                return intval($field_value);

            default:
                return null;
        }
    }


    /**
     * @return int
     */
    public function getRatingId() : int
    {
        return $this->rating_id;
    }


    /**
     * @param int $rating_id
     */
    public function setRatingId(int $rating_id)/*: void*/
    {
        $this->rating_id = $rating_id;
    }


    /**
     * @return int
     */
    public function getObjId() : int
    {
        return $this->obj_id;
    }


    /**
     * @param int $obj_id
     */
    public function setObjId(int $obj_id)/*: void*/
    {
        $this->obj_id = $obj_id;
    }


    /**
     * @return int
     */
    public function getUserId() : int
    {
        return $this->user_id;
    }


    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id)/*: void*/
    {
        $this->user_id = $user_id;
    }
}
