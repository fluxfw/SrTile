<?php

namespace srag\Plugins\SrTile\LearningProgressLegend;

use ilPanelGUI;
use ilSrTilePlugin;
use ilTemplate;
use ilUtil;
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


	public function render() {
		$tpl = new ilTemplate("tpl.lp_legend.html", true, true, "Services/Tracking");
		$tpl->setVariable("IMG_NOT_ATTEMPTED", ilUtil::getImagePath("scorm/not_attempted.svg"));
		$tpl->setVariable("IMG_IN_PROGRESS", ilUtil::getImagePath("scorm/incomplete.svg"));
		$tpl->setVariable("IMG_COMPLETED", ilUtil::getImagePath("scorm/completed.svg"));
		$tpl->setVariable("IMG_FAILED", ilUtil::getImagePath("scorm/failed.svg"));
		$tpl->setVariable("TXT_NOT_ATTEMPTED", self::dic()->language()->txt("trac_not_attempted"));
		$tpl->setVariable("TXT_IN_PROGRESS", self::dic()->language()->txt("trac_in_progress"));
		$tpl->setVariable("TXT_COMPLETED", self::dic()->language()->txt("trac_completed"));
		$tpl->setVariable("TXT_FAILED", self::dic()->language()->txt("trac_failed"));

		$panel = ilPanelGUI::getInstance();
		$panel->setPanelStyle(ilPanelGUI::PANEL_STYLE_SECONDARY);
		$panel->setBody($tpl->get());

		return $panel->getHTML();
	}
}