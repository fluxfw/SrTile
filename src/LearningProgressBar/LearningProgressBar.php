<?php

namespace srag\Plugins\SrTile\LearningProgressBar;

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
class LearningProgressBar {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var int
	 *
	 */
	protected $user_id;
	/**
	 * @var int
	 *
	 */
	protected $ref_id;
	/**
	 * @var int
	 *
	 */
	protected $total_objects = 1;
	/**
	 * @var int
	 *
	 */
	protected $completed_objects = 0;


	/**
	 * Rating constructor
	 *
	 * @param int $user_id
	 * @param int $obj_id
	 */
	public function __construct(/*int*/
		$user_id = 0, /*int*/
		$ref_id = 0) {

		$this->user_id = $user_id;
		$this->ref_id = $ref_id;

		$this->read();
	}


	private function read() {
		/**
		 * @var $database \ilDBInterface
		 */
		$database = self::dic()->database();

		$query = "SELECT count(mark.status) as total, SUM(if(mark.status = 2, 1, 0)) as completed, usr_id, collection.obj_id FROM ut_lp_collections as collection
				inner join object_reference as obj_ref on obj_ref.obj_id = collection.obj_id
				inner join object_reference as sub_obj on sub_obj.ref_id = collection.item_id
				inner join ut_lp_marks as mark on mark.obj_id = sub_obj.obj_id 
                where obj_ref.ref_id = " . $database->quote($this->ref_id, "integer") . " and mark.usr_id = "
			. $database->quote($this->user_id, "integer");

		$result = $database->query($query);

		$crs_data = array();



		while ($row = $database->fetchAssoc($result)) {
			if($row['total'] > 0) {
				$this->setTotalObjects(intval($row['total']));
			}
			$this->setCompletedObjects(intval($row['completed']));
		}
	}


	/**
	 * @return int
	 */
	public function getUserId(): int {
		return $this->user_id;
	}


	/**
	 * @param int $user_id
	 */
	public function setUserId(int $user_id) {
		$this->user_id = $user_id;
	}


	/**
	 * @return int
	 */
	public function getRefId(): int {
		return $this->ref_id;
	}


	/**
	 * @param int $ref_id
	 */
	public function setRefId(int $ref_id) {
		$this->ref_id = $ref_id;
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
