<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilLPStatus;
use ilSrTilePlugin;
use ilUIPluginRouterGUI;
use srag\CustomInputGUIs\SrTile\CustomInputGUIsTrait;
use srag\CustomInputGUIs\SrTile\ViewControlModeGUI\ViewControlModeGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressFilterGUI
 *
 * @package srag\Plugins\SrTile\LearningProgress
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressFilterGUI {

	use DICTrait;
	use SrTileTrait;
	use CustomInputGUIsTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const ID = "LearningProgressFilter";


	/**
	 * LearningProgressFilterGUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @return ViewControlModeGUI
	 */
	public function generateGUI(): ViewControlModeGUI {
		self::dic()->language()->loadLanguageModule("trac");

		return self::customInputGUIs()->viewControlModeGUI()->withId(self::ID)->withLink(self::dic()->ctrl()
			->getLinkTargetByClass([ ilUIPluginRouterGUI::class, TileGUI::class ], ViewControlModeGUI::CMD_HANDLE_BUTTONS))
			->withDefaultActiveId(ilLPStatus::LP_STATUS_IN_PROGRESS_NUM)->withButtons(array_map(function (string $txt): string {
				return self::dic()->language()->txt("trac_" . $txt);
			}, [
				"all" => "all",
				ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM => "not_attempted",
				ilLPStatus::LP_STATUS_IN_PROGRESS_NUM => "in_progress",
				ilLPStatus::LP_STATUS_COMPLETED_NUM => "completed",
				//ilLPStatus::LP_STATUS_FAILED_NUM => "failed"
			]));
	}


	/**
	 * @return string
	 */
	public function render(): string {
		return self::output()->getHTML($this->generateGUI());
	}
}
