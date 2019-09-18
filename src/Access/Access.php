<?php

namespace srag\Plugins\SrTile\Access;

use ilObjCourse;
use ilObjCourseAccess;
use ilObjGroup;
use ilObjGroupAccess;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Access
 *
 * @package srag\Plugins\SrTile\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Access
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @var bool[]
     */
    protected static $has_open_access = [];
    /**
     * @var bool[]
     */
    protected static $has_read_access = [];
    /**
     * @var bool[]
     */
    protected static $has_visible_access = [];
    /**
     * @var bool[]
     */
    protected static $has_write_access = [];


    /**
     * Access constructor
     */
    private function __construct()
    {

    }


    /**
     * @param Tile $tile
     *
     * @return bool
     */
    public function hasOpenAccess(Tile $tile) : bool
    {
        if (!isset(self::$has_open_access[$tile->getObjRefId()])) {
            if ($this->hasReadAccess($tile->getObjRefId())) {
                self::$has_open_access[$tile->getObjRefId()] = true;
            } else {
                if ($tile->_getIlObject() instanceof ilObjCourse) {
                    self::$has_open_access[$tile->getObjRefId()] = (new ilObjCourseAccess())->_checkAccess("join", "join", $tile->getObjRefId(), self::dic()
                        ->objDataCache()->lookupObjId($tile->getObjRefId()));
                } else {
                    if ($tile->_getIlObject() instanceof ilObjGroup) {
                        self::$has_open_access[$tile->getObjRefId()] = (new ilObjGroupAccess())->_checkAccess("join", "join", $tile->getObjRefId(), self::dic()
                            ->objDataCache()->lookupObjId($tile->getObjRefId()));
                    } else {
                        self::$has_open_access[$tile->getObjRefId()] = false;
                    }
                }
            }
        }

        return self::$has_open_access[$tile->getObjRefId()];
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasReadAccess(int $obj_ref_id) : bool
    {
        if (!isset(self::$has_read_access[$obj_ref_id])) {
            self::$has_read_access[$obj_ref_id] = self::dic()->access()->checkAccess("read", "", $obj_ref_id);
        }

        return self::$has_read_access[$obj_ref_id];
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasVisibleAccess(int $obj_ref_id) : bool
    {
        if (!isset(self::$has_visible_access[$obj_ref_id])) {
            self::$has_visible_access[$obj_ref_id] = self::dic()->access()->checkAccess("visible", "", $obj_ref_id);
        }

        return self::$has_visible_access[$obj_ref_id];
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasWriteAccess(int $obj_ref_id) : bool
    {
        if (!isset(self::$has_write_access[$obj_ref_id])) {
            self::$has_write_access[$obj_ref_id] = self::dic()->access()->checkAccess("write", "", $obj_ref_id);
        }

        return self::$has_write_access[$obj_ref_id];
    }
}
