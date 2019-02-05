<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\SrTile\Utils\SrTileTrait;
use srag\RemovePluginDataConfirm\SrTile\AbstractRemovePluginDataConfirm;

/**
 * Class SrTileRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy SrTileRemoveDataConfirm: ilUIPluginRouterGUI
 */
class SrTileRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
}
