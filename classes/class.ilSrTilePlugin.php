<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../Certificate/vendor/autoload.php")) {
	require_once __DIR__ . "/../../Certificate/vendor/autoload.php";
}

use srag\DIC\SrTile\Util\LibraryLanguageInstaller;
use srag\Plugins\SrTile\ColorThiefCache\ColorThiefCache;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\LearningProgress\LearningProgressFilter;
use srag\Plugins\SrTile\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrTile\Notification\Notification\Notification;
use srag\Plugins\SrTile\Rating\Rating;
use srag\Plugins\SrTile\Template\Template;
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
	const EVENT_CHANGE_TILE_BEFORE_RENDER = "change_title_before_render";
	/**
	 * @var self|null
	 */
	protected static $instance = null;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === null) {
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
	public function updateLanguages($a_lang_keys = null) {
		parent::updateLanguages($a_lang_keys);

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();

		LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
			. "/../vendor/srag/notifications4plugin/lang")->updateLanguages();
	}


	/**
	 * @inheritdoc
	 */
	protected function deleteData()/*: void*/ {
		self::dic()->database()->dropTable(ColorThiefCache::TABLE_NAME, false);
		self::dic()->database()->dropTable(Config::TABLE_NAME, false);
		self::dic()->database()->dropTable(Rating::TABLE_NAME, false);
		self::dic()->database()->dropTable(LearningProgressFilter::TABLE_NAME, false);
		Notification::dropDB_();
		NotificationLanguage::dropDB_();
		self::dic()->database()->dropTable(Template::TABLE_NAME, false);
		self::dic()->database()->dropTable(Tile::TABLE_NAME, false);

		ilUtil::delDir(ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . self::WEB_DATA_FOLDER);
	}
}
