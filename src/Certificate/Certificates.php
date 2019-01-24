<?php

namespace srag\Plugins\SrTile\Certificate;

use ilCertificate;
use ilCourseParticipants;
use ilLPStatus;
use ilObjCourseGUI;
use ilObject;
use ilObjUser;
use ilRepositoryGUI;
use ilSAHSPresentationGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Certificates
 *
 * @package srag\Plugins\SrTile\Certificate
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Certificates {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self[]
	 */
	protected static $instances = [];


	/**
	 * @param ilObjUser $user
	 * @param int       $obj_ref_id
	 *
	 * @return self
	 */
	public static function getInstance(ilObjUser $user, int $obj_ref_id): self {
		if (!isset(self::$instances[$user->getId() . "_" . $obj_ref_id])) {
			self::$instances[$user->getId() . "_" . $obj_ref_id] = new self($user, $obj_ref_id);
		}

		return self::$instances[$user->getId() . "_" . $obj_ref_id];
	}


	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var int
	 */
	protected $obj_ref_id;
	/**
	 * @var int
	 */
	protected $obj_id;


	/**
	 * Certificates constructor
	 *
	 * @param ilObjUser $user
	 * @param int       $obj_ref_id
	 */
	private function __construct(ilObjUser $user, int $obj_ref_id) {
		$this->user = $user;
		$this->obj_ref_id = $obj_ref_id;
		$this->obj_id = ilObject::_lookupObjectId($obj_ref_id);
	}


	/**
	 * @return bool
	 */
	public function enabled(): bool {
		return ilCertificate::isActive() && ilCertificate::isObjectActive($this->obj_id);
	}


	/**
	 * @return string|null
	 */
	public function getCertificateDownloadLink()/*: ?string*/ {
		switch (self::dic()->objDataCache()->lookupType($this->obj_id)) {
			case "crs":
				//@see Modules/Course/classes/class.ilObjCourseGUI.php:3214
				if (ilCourseParticipants::getDateTimeOfPassed($this->obj_id, $this->user->getId())) {
					self::dic()->ctrl()->setParameterByClass(ilObjCourseGUI::class, "ref_id", $this->obj_ref_id);

					return self::dic()->ctrl()->getLinkTargetByClass([ ilRepositoryGUI::class, ilObjCourseGUI::class ], 'deliverCertificate');
				}
				break;

			case "sahs":
				if (self::ilias()->learningProgress($this->user)->getStatus($this->obj_ref_id) === ilLPStatus::LP_STATUS_COMPLETED_NUM) {
					//the following way of link generation does not work! the above way is the standard(!:-( ILIAS way of link generation for certificate
					//$this->ctrl->setParameterByClass(ilSAHSPresentationGUI::class, 'ref_id', $obj_ref_id);
					//return $this->ctrl->getLinkTargetByClass(ilSAHSPresentationGUI::class,'downloadCertificate');
					return 'ilias.php?baseClass=' . ilSAHSPresentationGUI::class . '&ref_id=' . $this->obj_ref_id . '&cmd=downloadCertificate';
				}
				break;

			case "tst":
				// TODO Certificates for ILIAS Test
				break;

			default:
				break;
		}

		return NULL;
	}
}
