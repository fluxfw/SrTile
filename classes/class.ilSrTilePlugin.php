<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../Notifications4Plugins/vendor/autoload.php")) {
	require_once __DIR__ . "/../../Notifications4Plugins/vendor/autoload.php";
}

use srag\Plugins\SrTile\ColorThiefCache\ColorThiefCache;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\Rating\Rating;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use srag\RemovePluginDataConfirm\SrTile\PluginUninstallTrait;

/**
 * Class ilSrTilePlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTilePlugin extends ilUserInterfaceHookPlugin {

	use PluginUninstallTrait;
	use SrTileTrait;
	const PLUGIN_ID = "srtile";
	const PLUGIN_NAME = "SrTile";
	const PLUGIN_CLASS_NAME = self::class;
	const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = SrTileRemoveDataConfirm::class;
	const WEB_DATA_FOLDER = self::PLUGIN_ID . "_data";
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
	 * ilSrTilePlugin constructor
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * @return string
	 */
	public function getPluginName(): string {
		return self::PLUGIN_NAME;
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(Config::TABLE_NAME, false);
		self::dic()->database()->dropTable(Tile::TABLE_NAME, false);
		self::dic()->database()->dropTable(Rating::TABLE_NAME, false);
		self::dic()->database()->dropTable(ColorThiefCache::TABLE_NAME, false);

		ilUtil::delDir(ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . self::WEB_DATA_FOLDER);
	}
}
