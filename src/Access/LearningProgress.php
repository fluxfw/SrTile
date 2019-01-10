<?php

namespace srag\Plugins\SrTile\Access;

use ilLearningProgressBaseGUI;
use ilLPObjSettings;
use ilLPStatus;
use ilObject;
use ilObjectLP;
use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgress
 *
 * @package srag\Plugins\SrTile\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgress {

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
	public static function getInstance(ilObjUser $user): self {
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
	private function __construct(ilObjUser $user) {
		$this->user = $user;
	}


	/**
	 * @return bool
	 */
	public function enabled(): bool {
		return (boolval(self::dic()->settings()->get("enable_tracking")) && boolval(self::dic()->settings()->get("lp_list_gui")));
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return string
	 */
	public function getIcon(int $obj_ref_id): string {
		$status = $this->getStatus($obj_ref_id);

		return ilLearningProgressBaseGUI::_getImagePathForStatus($status);
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return int
	 */
	public function getStatus(int $obj_ref_id): int {
		if (!isset(self::$status_cache[$obj_ref_id])) {
			$obj_id = intval(ilObject::_lookupObjectId($obj_ref_id));

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
	public function getText(int $obj_ref_id): string {
		$status = $this->getStatus($obj_ref_id);

		return ilLearningProgressBaseGUI::_getStatusText($status);
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return bool
	 */
	public function hasLearningProgress(int $obj_ref_id): bool {
		if (!isset(self::$has_learning_progress[$obj_ref_id])) {
			$olp = ilObjectLP::getInstance(ilObject::_lookupObjectId($obj_ref_id));

			$a_mode = $olp->getCurrentMode();

			if (in_array($a_mode, [ ilLPObjSettings::LP_MODE_UNDEFINED, ilLPObjSettings::LP_MODE_DEACTIVATED ])) {
				self::$has_learning_progress[$obj_ref_id] = false;
			}

			self::$has_learning_progress[$obj_ref_id] = true;
		}

		return self::$has_learning_progress[$obj_ref_id];
	}
}
