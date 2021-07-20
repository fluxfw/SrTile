<?php

namespace srag\Plugins\SrTile\Config;

use ilCheckboxInputGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrTile\Config
 */
class ConfigFormGUI extends PropertyFormGUI
{

    use SrTileTrait;

    const KEY_ENABLED_OBJECT_LINKS = "enabled_object_links";
    const KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT = "enabled_object_links_once_select";
    const KEY_ENABLED_ON_DASHBOARD = "enabled_on_favorites";
    const KEY_ENABLED_ON_REPOSITORY = "enabled_on_repository";
    const LANG_MODULE = ConfigCtrl::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


    /**
     * ConfigFormGUI constructor
     *
     * @param ConfigCtrl $parent
     */
    public function __construct(ConfigCtrl $parent)
    {
        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(string $key)
    {
        switch ($key) {
            default:
                return self::srTile()->config()->getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands() : void
    {
        $this->addCommandButton(ConfigCtrl::CMD_UPDATE_CONFIGURE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields() : void
    {
        $this->fields = [
            self::KEY_ENABLED_ON_REPOSITORY => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            self::KEY_ENABLED_ON_DASHBOARD  => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class
            ],
            self::KEY_ENABLED_OBJECT_LINKS  => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                self::PROPERTY_SUBITEMS => [
                    self::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT => [
                        self::PROPERTY_CLASS => ilCheckboxInputGUI::class
                    ]
                ]
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId() : void
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle() : void
    {
        $this->setTitle($this->txt("configuration"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(string $key, $value) : void
    {
        switch ($key) {
            default:
                self::srTile()->config()->setValue($key, $value);
                break;
        }
    }
}
