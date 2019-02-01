<?php

namespace srag\Plugins\SrTile\Certificate;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class CertificateGUI
 *
 * @package srag\Plugins\SrTile\Certificate
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class CertificateGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var int
	 */
	protected $obj_ref_id;


	/**
	 * CertificateGUI constructor
	 *
	 * @param ilObjUser $user
	 * @param int       $obj_ref_id
	 */
	public function __construct(ilObjUser $user, $obj_ref_id) {
		$this->user = $user;
		$this->obj_ref_id = $obj_ref_id;
	}


	/**
	 * @return string
	 */
	public function render(): string {
		$certificates = self::ilias()->certificates($this->user, $this->obj_ref_id);

		$link = $certificates->getCertificateDownloadLink();

		if (empty($link)) {
			return '';
		}

		$tpl = self::plugin()->template("Certificate/certificate.html");

		$tpl->setVariable("CERTIFICATE_LINK", $link);
		$tpl->setVariable("CERTIFICATE_TEXT", self::plugin()->translate("download_certificate", TileGUI::LANG_MODULE_TILE));
		$tpl->setVariable("CERTIFICATE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/certificate.svg");

		return self::output()->getHTML($tpl);
	}
}
