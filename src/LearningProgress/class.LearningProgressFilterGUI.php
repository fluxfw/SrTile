<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilLink;
use ilLPStatus;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilSubmitButton;
use ilUIPluginRouterGUI;
use srag\CustomInputGUIs\SrTile\MultiSelectSearchInputGUI\MultiSelectSearchInputGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Tile\Tiles;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class LearningProgressFilterGUI
 *
 * @package           srag\Plugins\SrTile\LearningProgress
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\LearningProgress\LearningProgressFilterGUI: ilUIPluginRouterGUI
 */
class LearningProgressFilterGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const CMD_SET_FILTER = "setFilter";
	const POST_VAR = "lp_filter";
	/**
	 * @var int
	 */
	protected $obj_ref_id;


	/**
	 * LearningProgressFilterGUI constructor
	 */
	public function __construct() {
		$this->obj_ref_id = intval(self::tiles()->filterRefId());
	}


	/**
	 *
	 */
	public function executeCommand()/*: void*/ {
		$next_class = self::dic()->ctrl()->getNextClass($this);

		switch ($next_class) {
			default:
				$cmd = self::dic()->ctrl()->getCmd();

				switch ($cmd) {
					case self::CMD_SET_FILTER:
						$this->{$cmd}();
						break;

					default:
						break;
				}
				break;
		}
	}


	/**
	 *
	 */
	public function initToolbar()/*: void*/ {
		self::dic()->language()->loadLanguageModule("trac");

		self::dic()->ctrl()->setParameterByClass(self::class, Tiles::GET_PARAM_REF_ID, $this->obj_ref_id);

		self::dic()->toolbar()->setFormAction(self::dic()->ctrl()->getFormActionByClass([ ilUIPluginRouterGUI::class, self::class ]));

		self::dic()->toolbar()->addText(self::plugin()->translate("learning_progress", TileGUI::LANG_MODULE_TILE));

		$filter = new MultiSelectSearchInputGUI("", self::POST_VAR);
		$filter->setOptions(array_map(function (string $txt): string {
			return self::dic()->language()->txt("trac_" . $txt);
		}, [
			ilLPStatus::LP_STATUS_NOT_ATTEMPTED_NUM => "not_attempted",
			ilLPStatus::LP_STATUS_IN_PROGRESS_NUM => "in_progress",
			ilLPStatus::LP_STATUS_COMPLETED_NUM => "completed",
			//ilLPStatus::LP_STATUS_FAILED_NUM => "failed"
		]));
		$filter->setValue(self::learningProgressFilters(self::dic()->user())->getFilter($this->obj_ref_id));
		self::dic()->toolbar()->addInputItem($filter);

		$apply_button = ilSubmitButton::getInstance();
		$apply_button->setCaption(self::plugin()->translate("apply", TileGUI::LANG_MODULE_TILE), false);
		$apply_button->setCommand(self::CMD_SET_FILTER);
		self::dic()->toolbar()->addButtonInstance($apply_button);
	}


	/**
	 *
	 */
	public function setFilter()/*: void*/ {
		$filter = filter_input(INPUT_POST, self::POST_VAR, FILTER_DEFAULT, FILTER_FORCE_ARRAY);
		if (!is_array($filter)) {
			$filter = [];
		}

		$filter = array_map(function (string $status): int {
			return intval($status);
		}, $filter);

		self::learningProgressFilters(self::dic()->user())->setFilter($this->obj_ref_id, $filter);

		if (!empty($this->obj_ref_id)) {
			self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->obj_ref_id));
		} else {
			self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
		}
	}
}
