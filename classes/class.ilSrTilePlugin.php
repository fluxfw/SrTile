<?php

require_once __DIR__ . "/../vendor/autoload.php";
if (file_exists(__DIR__ . "/../../Certificate/vendor/autoload.php")) {
    require_once __DIR__ . "/../../Certificate/vendor/autoload.php";
}

use ILIAS\DI\Container;
use srag\CustomInputGUIs\SrTile\Loader\CustomInputGUIsLoaderDetector;
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

    const EVENT_CHANGE_TILE_BEFORE_RENDER = "change_title_before_render";
    const EVENT_SHOULD_NOT_DISPLAY_ALERT_MESSAGE = "should_not_display_alert_message";
    const PLUGIN_CLASS_NAME = self::class;
    const PLUGIN_ID = "srtile";
    const PLUGIN_NAME = "SrTile";
    const WEB_DATA_FOLDER = self::PLUGIN_ID . "_data";
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * ilSrTilePlugin constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


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
     * @inheritDoc
     */
    public function exchangeUIRendererAfterInitialization(Container $dic) : Closure
    {
        return CustomInputGUIsLoaderDetector::exchangeUIRendererAfterInitialization();
    }


    /**
     * @inheritDoc
     */
    public function getPluginName() : string
    {
        return self::PLUGIN_NAME;
    }


    /**
     * @inheritDoc
     */
    public function handleEvent(/*string*/ $a_component, /*string*/ $a_event, /*array*/ $a_parameter)/* : void*/
    {
        switch ($a_component) {
            case "Services/Object":
                switch ($a_event) {
                    case "cloneObject":
                        self::srTile()->tiles()->cloneTile($a_parameter["cloned_from_object"]->getRefId(), $a_parameter["object"]->getRefId());
                        break;
                    default:
                        break;
                }
                break;

            default:
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function updateLanguages(/*?array*/ $a_lang_keys = null)/*:void*/
    {
        parent::updateLanguages($a_lang_keys);

        $this->installRemovePluginDataConfirmLanguages();

        self::srTile()->notifications4plugin()->installLanguages();
    }


    /**
     * @inheritDoc
     */
    protected function deleteData()/*: void*/
    {
        self::srTile()->dropTables();
    }


    /**
     * @inheritDoc
     */
    protected function shouldUseOneUpdateStepOnly() : bool
    {
        return true;
    }
}
