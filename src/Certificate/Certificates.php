<?php

namespace srag\Plugins\SrTile\Certificate;

use ilCertificate;
use ilLPStatus;
use ilObject;
use ilObjUser;
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
		return ilCertificate::isActive();
	}


	/**
	 * @return string|null
	 */
	public function getCertificateBatchForUserOnCourseOrCourseSubModule()/*: ?string*/ {
		switch (ilObject::_lookupType($this->obj_id)) {
			case 'crs':
				return $this->getCertificateBatchOfModuleWithActiveCertificateInCourse();

			default:
				return $this->getCertificateBatchOfModuleWithActiveCertificate();
		}
	}


	/**
	 * @return string|null
	 */
	protected function getCertificateBatchOfModuleWithActiveCertificateInCourse()/*: ?string*/ {
		$query = 'SELECT cert.obj_id AS mod_obj_id, mod_ref.ref_id AS mod_ref_id FROM il_certificate AS cert
					INNER JOIN object_reference AS mod_ref ON mod_ref.obj_id = cert.obj_id
					INNER JOIN tree ON tree.child = mod_ref.ref_id
					INNER JOIN object_reference AS crs_ref ON crs_ref.ref_id = tree.parent
					INNER JOIN object_data AS crs_obj ON crs_obj.obj_id = crs_ref.obj_id
						AND crs_obj.type=%s AND crs_obj.obj_id=%s';

		$res = self::dic()->database()->queryF($query, [ "text", "integer" ], [ "crs", $this->obj_id ]);

		while (($row = $res->fetchAssoc()) !== false) {
			return $this->returnDownloadLink($row['mod_ref_id']);
		}

		return NULL;
	}


	/**
	 * @return string|null
	 */
	protected function getCertificateBatchOfModuleWithActiveCertificate()/*: ?string*/ {
		$query = 'SELECT cert.obj_id AS mod_obj_id, mod_ref.ref_id AS mod_ref_id FROM il_certificate AS cert
					INNER JOIN object_reference AS mod_ref ON mod_ref.obj_id = cert.obj_id
					WHERE cert.obj_id=%s';

		$res = self::dic()->database()->queryF($query, [ "integer" ], [ $this->obj_id ]);

		while (($row = $res->fetchAssoc()) !== false) {
			return $this->returnDownloadLink($row['mod_ref_id']);
		}

		return NULL;
	}


	/**
	 * @param int $obj_ref_id
	 *
	 * @return string|null
	 */
	protected function returnDownloadLink(int $obj_ref_id)/*: ?string*/ {
		if ($obj_ref_id > 0 && self::ilias()->learningProgress($this->user)->getStatus($obj_ref_id) == ilLPStatus::LP_STATUS_COMPLETED_NUM) {
			return 'ilias.php?baseClass=' . ilSAHSPresentationGUI::class . '&ref_id=' . $obj_ref_id . '&cmd=downloadCertificate';

			//the following way of link generation does not work! the above way is the standard(!:-( ILIAS way of link generation for certificate
			//$this->ctrl->setParameterByClass(ilSAHSPresentationGUI::class, 'ref_id', $obj_ref_id);
			//return $this->ctrl->getLinkTargetByClass(ilSAHSPresentationGUI::class,'downloadCertificate');
		} else {
			return NULL;
		}
	}
}
