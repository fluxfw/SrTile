<?php

namespace srag\Plugins\SrTile\ColorThiefCache;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ColorThiefCaches
 *
 * @package srag\Plugins\SrTile\ColorThiefCache
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ColorThiefCaches {

	use SrTileTrait;
	use DICTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self|null
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
	 * ColorThiefCaches constructor
	 */
	private function __construct() {

	}


	/**
	 * @param string $image_path
	 *
	 * @return ColorThiefCache
	 */
	public function getColorThiefCache(string $image_path): ColorThiefCache {
		/**
		 * @var ColorThiefCache $colorThiefCache
		 */

		$colorThiefCache = ColorThiefCache::where([
			"image_path" => $image_path
		])->first();

		if ($colorThiefCache === NULL) {
			$colorThiefCache = new ColorThiefCache();

			$colorThiefCache->setImagePath($image_path);
		}

		return $colorThiefCache;
	}


	/**
	 * @param string $image_path
	 */
	public function delete(string $image_path)/*: void*/ {
		/**
		 * @var ColorThiefCache $colorThiefCache
		 */

		$colorThiefCache = ColorThiefCache::where([
			"image_path" => $image_path
		])->first();

		if ($colorThiefCache === NULL) {
			$colorThiefCache->delete();
		}
	}
}
