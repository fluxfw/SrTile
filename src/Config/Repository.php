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
 */
final class Repository extends AbstractRepository
{

    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    protected function __construct()
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
    protected function getFields() : array
    {
        return [
            ConfigFormGUI::KEY_ENABLED_ON_DASHBOARD             => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_ENABLED_ON_REPOSITORY            => [Config::TYPE_BOOLEAN, true],
            ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS             => [Config::TYPE_BOOLEAN, false],
            ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT => [Config::TYPE_BOOLEAN, false]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return "ui_uihk_" . ilSrTilePlugin::PLUGIN_ID . "_config";
    }
}
