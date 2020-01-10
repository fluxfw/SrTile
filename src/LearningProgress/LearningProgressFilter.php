<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ActiveRecord;
use arConnector;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressFilter
 *
 * @package srag\Plugins\SrTile\LearningProgress
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressFilter extends ActiveRecord
{

    use DICTrait;
    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_srtile_lp_fil";
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
    protected $filter_id;
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
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $user_id;
    /**
     * @var array
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $filter = [];


    /**
     * LearningProgressFilter constructor
     *
     * @param int              $primary_key_filter
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/
        $primary_key_filter = 0,
        arConnector $connector = null
    ) {
        parent::__construct($primary_key_filter, $connector);
    }


    /**
     * @return string
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_filter = $this->{$field_name};

        switch ($field_name) {
            case "filter":
                return json_encode($field_filter);

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
            case "filter_id":
            case "obj_ref_id":
            case "user_id":
                return intval($field_value);

            case "filter":
                return json_decode($field_value);

            default:
                return null;
        }
    }


    /**
     * @return int
     */
    public function getFilterId() : int
    {
        return $this->filter_id;
    }


    /**
     * @param int $filter_id
     */
    public function setFilterId(int $filter_id)/*: void*/
    {
        $this->filter_id = $filter_id;
    }


    /**
     * @return int
     */
    public function getObjRefId() : int
    {
        return $this->obj_ref_id;
    }


    /**
     * @param int $obj_ref_id
     */
    public function setObjRefId(int $obj_ref_id)/*: void*/
    {
        $this->obj_ref_id = $obj_ref_id;
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


    /**
     * @return array
     */
    public function getFilter() : array
    {
        return $this->filter;
    }


    /**
     * @param array $filter
     */
    public function setFilter(array $filter) /*: void*/
    {
        $this->filter = $filter;
    }
}
