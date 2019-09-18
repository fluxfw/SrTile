<?php

namespace srag\Plugins\SrTile\Config;

use ilSrTilePlugin;
use srag\ActiveRecordConfig\SrTile\ActiveRecordConfig;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Config
 *
 * @package srag\Plugins\SrTile\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Config extends ActiveRecordConfig
{

    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_srtile_config";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const KEY_ENABLED_ON_FAVORITES = "enabled_on_favorites";
    const KEY_ENABLED_ON_REPOSITORY = "enabled_on_repository";
    /**
     * @var array
     */
    protected static $fields
        = [
            self::KEY_ENABLED_ON_FAVORITES  => [self::TYPE_BOOLEAN, true],
            self::KEY_ENABLED_ON_REPOSITORY => [self::TYPE_BOOLEAN, true]
        ];
}
