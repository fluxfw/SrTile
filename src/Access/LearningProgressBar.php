<?php

namespace srag\Plugins\SrTile\Access;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressBar
 *
 * @package srag\Plugins\SrTile\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressBar {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self[]
	 */
	protected static $instances = [];


	/**
	 * @param ilObjUser $user
	 * @param int       $ref_id
	 *
	 * @return self
	 */
	public static function getInstance(ilObjUser $user, int $ref_id): self {
		if (!isset(self::$instances[$user->getId() . "_" . $ref_id])) {
			self::$instances[$user->getId() . "_" . $ref_id] = new self($user, $ref_id);
		}

		return self::$instances[$user->getId() . "_" . $ref_id];
	}


	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var int
	 */
	protected $ref_id;
	/**
	 * @var int
	 */
	protected $total_objects = 1;
	/**
	 * @var int
	 */
	protected $completed_objects = 0;


	/**
	 * LearningProgressBar constructor
	 *
	 * @param ilObjUser $user
	 * @param int $obj_id
	 */
	public function __construct(ilObjUser $user, int $ref_id) {
		$this->user = $user;
		$this->ref_id = $ref_id;

		$this->read();
	}


	/**
	 *
	 */
	private function read() {
		$query = "SELECT count(mark.status) as total, SUM(if(mark.status = 2, 1, 0)) as completed, usr_id, collection.obj_id FROM ut_lp_collections as collection
				inner join object_reference as obj_ref on obj_ref.obj_id = collection.obj_id
				inner join object_reference as sub_obj on sub_obj.ref_id = collection.item_id
				inner join ut_lp_marks as mark on mark.obj_id = sub_obj.obj_id 
                where obj_ref.ref_id = " . self::dic()->database()->quote($this->ref_id, "integer") . " and mark.usr_id = " . self::dic()->database()
				->quote($this->user->getId(), "integer");

		$result = self::dic()->database()->query($query);

		while (($row = $result->fetchAssoc()) !== false) {
			if ($row['total'] > 0) {
				$this->setTotalObjects(intval($row['total']));
			}
			$this->setCompletedObjects(intval($row['completed']));
		}
	}


	/**
	 * @return int
	 */
	public function getTotalObjects(): int {
		return $this->total_objects;
	}


	/**
	 * @param int $total_objects
	 */
	public function setTotalObjects(int $total_objects) {
		$this->total_objects = $total_objects;
	}


	/**
	 * @return int
	 */
	public function getCompletedObjects(): int {
		return $this->completed_objects;
	}


	/**
	 * @param int $completed_objects
	 */
	public function setCompletedObjects(int $completed_objects) {
		$this->completed_objects = $completed_objects;
	}
}
