<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilLearningProgressBaseGUI;
use ilLPObjSettings;
use ilLPStatus;
use ilObjectLP;
use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgress
 *
 * @package srag\Plugins\SrTile\LearningProgress
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgress
{

    use SrTileTrait;
    use DICTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self[]
     */
    protected static $instances = [];


    /**
     * @param ilObjUser $user
     *
     * @return self
     */
    public static function getInstance(ilObjUser $user) : self
    {
        if (!isset(self::$instances[$user->getId()])) {
            self::$instances[$user->getId()] = new self($user);
        }

        return self::$instances[$user->getId()];
    }


    /**
     * @var int[]
     */
    protected static $status_cache = [];
    /**
     * @var bool[]
     */
    protected static $has_learning_progress = [];
    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * LearningProgress constructor
     *
     * @param ilObjUser $user
     */
    private function __construct(ilObjUser $user)
    {
        $this->user = $user;
    }


    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return (boolval(self::dic()->settings()->get("enable_tracking")) && boolval(self::dic()->settings()->get("lp_list_gui")));
    }


    /**
     * @param int $obj_ref_id
     *
     * @return string
     */
    public function getIcon(int $obj_ref_id) : string
    {
        if (!$this->enabled()) {
            return "";
        }

        $status = $this->getStatus($obj_ref_id);
        if ($status === 0) {
            // Why this fix is needed? 0 == 1 ?!
            $status = ilLPStatus::LP_STATUS_NOT_ATTEMPTED;
        }

        switch ($status) {
            case ilLPStatus::LP_STATUS_IN_PROGRESS_NUM:
            case ilLPStatus::LP_STATUS_IN_PROGRESS:
            case ilLPStatus::LP_STATUS_REGISTERED:
                return self::plugin()->directory() . "/templates/images/LearningProgress/incompleted.svg";

            case ilLPStatus::LP_STATUS_COMPLETED_NUM:
            case ilLPStatus::LP_STATUS_COMPLETED:
            case ilLPStatus::LP_STATUS_PARTICIPATED:
                return self::plugin()->directory() . "/templates/images/LearningProgress/completed.svg";

            case ilLPStatus::LP_STATUS_FAILED_NUM:
            case ilLPStatus::LP_STATUS_FAILED:
                return self::plugin()->directory() . "/templates/images/LearningProgress/failed.svg";

            case ilLPStatus::LP_STATUS_NOT_ATTEMPTED:
            case ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM:
            case ilLPStatus::LP_STATUS_NOT_PARTICIPATED:
            case ilLPStatus::LP_STATUS_NOT_REGISTERED:
            default:
                return self::plugin()->directory() . "/templates/images/LearningProgress/not_attempted.svg";
        }
    }


    /**
     * @param int $obj_ref_id
     *
     * @return int
     */
    public function getStatus(int $obj_ref_id) : int
    {
        if (!$this->enabled()) {
            return 0;
        }
        if (!$this->hasLearningProgress($obj_ref_id)) {
            return false;
        }

        if (!isset(self::$status_cache[$obj_ref_id])) {
            $obj_id = intval(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

            // Avoid exit
            if (ilObjectLP::getInstance($obj_id)->getCurrentMode() != ilLPObjSettings::LP_MODE_UNDEFINED) {
                $status = intval(ilLPStatus::_lookupStatus($obj_id, $this->user->getId()));
            } else {
                $status = ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM;
            }

            self::$status_cache[$obj_ref_id] = $status;
        }

        return self::$status_cache[$obj_ref_id];
    }


    /**
     * @param int $obj_ref_id
     *
     * @return string
     */
    public function getText(int $obj_ref_id) : string
    {
        if (!$this->enabled()) {
            return "";
        }

        $status = $this->getStatus($obj_ref_id);

        return ilLearningProgressBaseGUI::_getStatusText($status);
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasLearningProgress(int $obj_ref_id) : bool
    {
        if (!$this->enabled()) {
            return false;
        }

        if (!isset(self::$has_learning_progress[$obj_ref_id])) {
            $olp = ilObjectLP::getInstance(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

            $a_mode = $olp->getCurrentMode();

            if (!in_array($a_mode, [ilLPObjSettings::LP_MODE_UNDEFINED, ilLPObjSettings::LP_MODE_DEACTIVATED])) {
                self::$has_learning_progress[$obj_ref_id] = true;
            } else {
                self::$has_learning_progress[$obj_ref_id] = false;
            }
        }

        return self::$has_learning_progress[$obj_ref_id];
    }
}
