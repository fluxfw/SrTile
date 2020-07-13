<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilDBConstants;
use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressBar
 *
 * @package srag\Plugins\SrTile\LearningProgress
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressBar
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self[]
     */
    protected static $instances = [];
    /**
     * @var int
     */
    protected $completed_objects = 0;
    /**
     * @var int
     */
    protected $obj_ref_id;
    /**
     * @var int
     */
    protected $total_objects = 1;
    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * LearningProgressBar constructor
     *
     * @param ilObjUser $user
     * @param int       $obj_ref_id
     */
    private function __construct(ilObjUser $user, int $obj_ref_id)
    {
        $this->user = $user;
        $this->obj_ref_id = $obj_ref_id;

        $this->read();
    }


    /**
     * @param ilObjUser $user
     * @param int       $obj_ref_id
     *
     * @return self
     */
    public static function getInstance(ilObjUser $user, int $obj_ref_id) : self
    {
        if (!isset(self::$instances[$user->getId() . "_" . $obj_ref_id])) {
            self::$instances[$user->getId() . "_" . $obj_ref_id] = new self($user, $obj_ref_id);
        }

        return self::$instances[$user->getId() . "_" . $obj_ref_id];
    }


    /**
     * @return int
     */
    public function getCompletedObjects() : int
    {
        return $this->completed_objects;
    }


    /**
     * @param int $completed_objects
     */
    public function setCompletedObjects(int $completed_objects)
    {
        $this->completed_objects = $completed_objects;
    }


    /**
     * @return int
     */
    public function getTotalObjects() : int
    {
        return $this->total_objects;
    }


    /**
     * @param int $total_objects
     */
    public function setTotalObjects(int $total_objects)
    {
        $this->total_objects = $total_objects;
    }


    /**
     *
     */
    private function read()
    {
        $query = "SELECT COUNT(mark.status) AS total, SUM(if(mark.status = 2, 1, 0)) AS completed, usr_id, collection.obj_id FROM ut_lp_collections AS collection
				INNER JOIN object_reference AS obj_ref ON obj_ref.obj_id = collection.obj_id
				INNER JOIN object_reference AS sub_obj ON sub_obj.ref_id = collection.item_id
				INNER JOIN ut_lp_marks AS mark ON mark.obj_id = sub_obj.obj_id 
                WHERE obj_ref.ref_id = " . self::dic()->database()->quote($this->obj_ref_id, ilDBConstants::T_INTEGER) . " AND mark.usr_id = "
            . self::dic()->database()->quote($this->user->getId(), ilDBConstants::T_INTEGER);

        $result = self::dic()->database()->query($query);

        while (($row = $result->fetchAssoc()) !== false) {
            if ($row['total'] > 0) {
                $this->setTotalObjects(intval($row['total']));
            }
            $this->setCompletedObjects(intval($row['completed']));
        }
    }
}
