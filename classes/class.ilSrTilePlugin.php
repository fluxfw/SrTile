<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../Certificate/vendor/autoload.php")) {
    require_once __DIR__ . "/../../Certificate/vendor/autoload.php";
}

use srag\DIC\SrTile\Util\LibraryLanguageInstaller;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use srag\RemovePluginDataConfirm\SrTile\PluginUninstallTrait;

/**
 * Class ilSrTilePlugin
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTilePlugin extends ilUserInterfaceHookPlugin
{

    use PluginUninstallTrait;
    use SrTileTrait;
    const PLUGIN_ID = "srtile";
    const PLUGIN_NAME = "SrTile";
    const PLUGIN_CLASS_NAME = self::class;
    const REMOVE_PLUGIN_DATA_CONFIRM_CLASS_NAME = SrTileRemoveDataConfirm::class;
    const WEB_DATA_FOLDER = self::PLUGIN_ID . "_data";
    const EVENT_CHANGE_TILE_BEFORE_RENDER = "change_title_before_render";
    const EVENT_SHOULD_NOT_DISPLAY_ALERT_MESSAGE = "should_not_display_alert_message";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * ilSrTilePlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return string
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritdoc
     */
    public function updateLanguages($a_lang_keys = null)
    {
        parent::updateLanguages($a_lang_keys);

        LibraryLanguageInstaller::getInstance()->withPlugin(self::plugin())->withLibraryLanguageDirectory(__DIR__
            . "/../vendor/srag/removeplugindataconfirm/lang")->updateLanguages();

        self::srTile()->notifications4plugin()->installLanguages();
    }


    /**
     * @inheritdoc
     */
    protected function deleteData()/*: void*/
    {
        self::srTile()->dropTables();
    }
}
