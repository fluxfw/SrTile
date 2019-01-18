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
class Config extends ActiveRecordConfig {

	use SrTileTrait;
	const TABLE_NAME = "ui_uihk_srtile_config";
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var array
	 */
	protected static $fields = [

	];
}
