<?php

namespace srag\Plugins\SrTile\Config;

use ilCheckboxInputGUI;
use ilSrTilePlugin;
use srag\ActiveRecordConfig\SrTile\ActiveRecordConfigFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrTile\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CONFIG_CLASS_NAME = Config::class;


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            Config::KEY_ENABLED_ON_REPOSITORY => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            Config::KEY_ENABLED_ON_FAVORITES  => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ]
        ];
    }
}
