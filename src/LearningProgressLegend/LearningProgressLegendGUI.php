<?php

namespace srag\Plugins\SrTile\LearningProgressLegend;

use ilPanelGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressLegendGUI
 *
 * @package srag\Plugins\SrTile\LearningProgressLegend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressLegendGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var self
	 */
	protected static $instance = NULL;


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return string
	 */
	public function render(): string {
		$tpl_legend = self::plugin()->template("LearningProgress/legend.html");

		$tpl_legend->setCurrentBlock("status");

		foreach ([
			         ilUtil::getImagePath("scorm/not_attempted.svg") => self::dic()->language()->txt("trac_not_attempted"),
			         ilUtil::getImagePath("scorm/incomplete.svg") => self::dic()->language()->txt("trac_in_progress"),
			         ilUtil::getImagePath("scorm/completed.svg") => self::dic()->language()->txt("trac_completed")
			         //ilUtil::getImagePath("scorm/failed.svg") => self::dic()->language()->txt("trac_failed")
		         ] as $img => $txt) {
			$tpl_legend->setVariable("IMG_STATUS", $img);
			$tpl_legend->setVariable("TXT_STATUS", $txt);
			$tpl_legend->parseCurrentBlock();
		}

		$panel = ilPanelGUI::getInstance();
		$panel->setPanelStyle(ilPanelGUI::PANEL_STYLE_SECONDARY);
		$panel->setBody(self::output()->getHTML($tpl_legend));

		return self::output()->getHTML($panel);
	}
}
