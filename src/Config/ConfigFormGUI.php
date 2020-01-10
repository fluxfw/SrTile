<?php

namespace srag\Plugins\SrTile\Config;

use ilCheckboxInputGUI;
use ilSrTileConfigGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\ConfigPropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrTile\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ConfigPropertyFormGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CONFIG_CLASS_NAME = Config::class;
    const LANG_MODULE = ilSrTileConfigGUI::LANG_MODULE;


    /**
     * ConfigFormGUI constructor
     *
     * @param ilSrTileConfigGUI $parent
     */
    public function __construct(ilSrTileConfigGUI $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(ilSrTileConfigGUI::CMD_UPDATE_CONFIGURE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            Config::KEY_ENABLED_ON_REPOSITORY => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            Config::KEY_ENABLED_ON_FAVORITES  => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            Config::KEY_ENABLED_OBJECT_LINKS  => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_SUBITEMS => [
                    Config::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT => [
                        self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                    ]
                ]
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("configuration"));
    }
}
