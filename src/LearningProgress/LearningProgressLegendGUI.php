<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilPanelGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressLegendGUI
 *
 * @package srag\Plugins\SrTile\LearningProgress
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressLegendGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


	/**
	 * LearningProgressLegendGUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @return string
	 */
	public function render(): string {
		self::dic()->language()->loadLanguageModule("trac");

		$tpl_legend = self::plugin()->template("LearningProgress/legend.html");

		$tpl_legend->setCurrentBlock("status");

		foreach ([
			         "not_attempted" => "not_attempted",
			         "incomplete" => "in_progress",
			         "completed" => "completed"
			         //"failed" => "failed"
		         ] as $img => $txt) {
			$tpl_legend->setVariable("IMG_STATUS", self::plugin()->directory() . "/templates/images/LearningProgress/" . $img . ".svg");
			$tpl_legend->setVariable("TXT_STATUS", self::dic()->language()->txt("trac_" . $txt));
			$tpl_legend->parseCurrentBlock();
		}

		$panel = ilPanelGUI::getInstance();
		$panel->setPanelStyle(ilPanelGUI::PANEL_STYLE_SECONDARY);
		$panel->setBody(self::output()->getHTML($tpl_legend));

		return self::output()->getHTML($panel);
	}
}
