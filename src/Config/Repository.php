<?php

namespace srag\Plugins\SrTile\Config;

use ilSrTilePlugin;
use srag\ActiveRecordConfig\SrTile\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrTile\Config\AbstractRepository;
use srag\ActiveRecordConfig\SrTile\Config\Config;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository extends AbstractRepository
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self
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
     * Repository constructor
     */
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return "ui_uihk_" . ilSrTilePlugin::PLUGIN_ID . "_config";
    }


    /**
     * @inheritDoc
     */
    protected function getFields() : array
    {
        return [
            ConfigFormGUI::KEY_ENABLED_ON_FAVORITES             => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_ENABLED_ON_REPOSITORY            => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS             => [Config::TYPE_BOOLEAN, false],
            ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT => [Config::TYPE_BOOLEAN, false]
        ];
    }
}
