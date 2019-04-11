<?php

namespace srag\Plugins\SrTile\Metadata;

use ilObject;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use ilMD;

/**
 * Class Metadata
 *
 * @package srag\Plugins\SrTile\Metadata
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Metadata {

	use SrTileTrait;
	use DICTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self[]
	 */
	protected static $instances = [];
	/**
	 * @var ilObject
	 */
	protected $il_object;


	/**
	 * Metadata constructor
	 *
	 * @param ilObject $il_object
	 */
	private function __construct(ilObject $il_object) {
		$this->il_object = $il_object;
	}


	/**
	 * @param ilObject $il_object
	 *
	 * @return self
	 */
	public static function getInstance(ilObject $il_object): self {
		if (!isset(self::$instances[$il_object->getId()])) {
			self::$instances[$il_object->getId()] = new self($il_object);
		}

		return self::$instances[$il_object->getId()];
	}


	/**
	 * @return string
	 */
	public function getLanguageFlagImagePath() {
		if(strlen($this->getLanguageCode()) > 0) {
			if(is_file(self::plugin()->directory() . "/templates/images/Language/" . $this->getLanguageCode() . ".png")) {
				return self::plugin()->directory() . "/templates/images/Language/" . $this->getLanguageCode() . ".png";
			}
		}

		return "";
	}


	/**
	 * @return string
	 */
	private function getLanguageCode(): string {
		$il_md = new ilMD($this->il_object->getId(), $this->il_object->getId(), $this->il_object->getType());
		/**
		 * var $md_language ilMDLanguage
		 */
		$md_language = $il_md->getGeneral()->getLanguage(2);

		return ($md_language->getLanguageCode() !== false ? $md_language->getLanguageCode() : "");
	}
}