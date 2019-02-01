<?php

namespace srag\Plugins\SrTile\Access;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Certificate\Certificates;
use srag\Plugins\SrTile\Favorite\Favorites;
use srag\Plugins\SrTile\LearningProgress\LearningProgress;
use srag\Plugins\SrTile\LearningProgress\LearningProgressBar;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\SrTile\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Ilias constructor
	 */
	private function __construct() {

	}


	/**
	 * @param ilObjUser $user
	 * @param int       $obj_ref_id
	 *
	 * @return Certificates
	 */
	public function certificates(ilObjUser $user, int $obj_ref_id): Certificates {
		return Certificates::getInstance($user, $obj_ref_id);
	}


	/**
	 * @return Courses
	 */
	public function courses(): Courses {
		return Courses::getInstance();
	}


	/**
	 * @param ilObjUser $user
	 *
	 * @return Favorites
	 */
	public function favorites(ilObjUser $user): Favorites {
		return Favorites::getInstance($user);
	}


	/**
	 * @param ilObjUser $user
	 *
	 * @return LearningProgress
	 */
	public function learningProgress(ilObjUser $user): LearningProgress {
		return LearningProgress::getInstance($user);
	}


	/**
	 * @param ilObjUser $user
	 *
	 * @return LearningProgressBar
	 */
	public function learningProgressBar(ilObjUser $user, int $obj_ref_id): LearningProgressBar {
		return LearningProgressBar::getInstance($user, $obj_ref_id);
	}
}
