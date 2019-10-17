<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrTile\ActiveRecordConfigGUI;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Notification\Ctrl\Notifications4PluginCtrl;
use srag\Plugins\SrTile\Template\TemplatesConfigGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ilSrTileConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTileConfigGUI extends ActiveRecordConfigGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var array
     */
    protected static $tabs
        = [
            self::TAB_CONFIGURATION                     => ConfigFormGUI::class,
            TemplatesConfigGUI::TAB_TEMPLATES           => [
                TemplatesConfigGUI::class,
                TemplatesConfigGUI::CMD_LIST_TEMPLATES
            ],
            Notifications4PluginCtrl::TAB_NOTIFICATIONS => [
                Notifications4PluginCtrl::class,
                Notifications4PluginCtrl::CMD_LIST_NOTIFICATIONS
            ]
        ];
}
