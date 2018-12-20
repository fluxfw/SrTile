<?php

namespace srag\Plugins\SrTile\Rating;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Ratings
 *
 * @package srag\Plugins\SrTile\Rating
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Ratings {

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
	 * @var ilObjUser
	 */
	protected $user;


	/**
	 * Ratings constructor
	 *
	 * @param ilObjUser $user
	 */
	private function __construct(ilObjUser $user) {
		$this->user = $user;
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return int
	 */
	public function getLikesCount($obj_ref_id): int {
		return Rating::where([
			"obj_ref_id" => $obj_ref_id
		])->count();
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return Rating|null
	 */
	public function getRating(int $obj_ref_id)/*: ?Rating*/ {
		/**
		 * @var Rating|null $rating
		 */

		$rating = Rating::where([
			"obj_ref_id" => $obj_ref_id,
			"user_id" => $this->user->getId()
		])->first();

		return $rating;
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return bool
	 */
	public function hasLike(int $obj_ref_id): bool {
		return ($this->getRating($obj_ref_id) !== NULL);
	}


	/**
	 * @param int $obj_ref_id
	 */
	public function like(int $obj_ref_id)/*: void*/ {
		$rating = $this->getRating($obj_ref_id);

		if ($rating === NULL) {
			$rating = new Rating();

			$rating->setObjRefId($obj_ref_id);

			$rating->setUserId($this->user->getId());

			$rating->store();
		}
	}


	/**
	 * @param int $obj_ref_id
	 */
	public function unlike(int $obj_ref_id)/*: void*/ {
		$rating = $this->getRating($obj_ref_id);

		if ($rating !== NULL) {
			$rating->delete();
		}
	}
}
