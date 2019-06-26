<?php

namespace srag\Plugins\SrTile\Notification\Ctrl;

use ilSrTilePlugin;
use srag\Notifications4Plugin\SrTile\Ctrl\AbstractCtrl;
use srag\Plugins\SrTile\Notification\Notification\Language\NotificationLanguage;
use srag\Plugins\SrTile\Notification\Notification\Notification;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Notifications4PluginCtrl
 *
 * @package           srag\Plugins\SrTile\Notification\Ctrl
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Notification\Ctrl\Notifications4PluginCtrl: ilSrTileConfigGUI
 */
class Notifications4PluginCtrl extends AbstractCtrl {

	use SrTileTrait;
	const NOTIFICATION_CLASS_NAME = Notification::class;
	const LANGUAGE_CLASS_NAME = NotificationLanguage::class;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


	/**
	 * @inheritdoc
	 */
	public function executeCommand()/*: void*/ {
		self::dic()->tabs()->activateTab(self::TAB_NOTIFICATIONS);

		parent::executeCommand();
	}
}
