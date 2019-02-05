<?php

namespace srag\Plugins\SrTile\Recommend;

use ilEMailInputGUI;
use ilNonEditableValueGUI;
use ilSrTilePlugin;
use ilTextAreaInputGUI;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class RecommendFormGUI
 *
 * @package srag\Plugins\SrTile\Recommend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommendFormGUI extends ObjectPropertyFormGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const LANG_MODULE = RecommendGUI::LANG_MODULE_RECOMMENDATION;
	/**
	 * @var Tile
	 */
	protected $tile;


	/**
	 * RecommendFormGUI constructor
	 *
	 * @param RecommendGUI $parent
	 * @param Tile         $tile
	 */
	public function __construct(RecommendGUI $parent, Tile $tile) {
		$this->tile = $tile;

		parent::__construct($parent, new Recommend($this->tile), false);
	}


	/**
	 * @inheritdoc
	 */
	protected final function initAction()/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent, "ref_id", $this->tile->getObjRefId());

		$this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(RecommendGUI::CMD_NEW_RECOMMEND, $this->txt("submit"), "tile_recommend_modal_submit");

		$this->addCommandButton("", $this->txt("cancel"), "tile_recommend_modal_cancel");

		$this->setShowTopButtons(false);
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			"recommend_to" => [
				self::PROPERTY_CLASS => ilEMailInputGUI::class,
				self::PROPERTY_REQUIRED => true
			],
			"message" => [
				self::PROPERTY_CLASS => ilTextAreaInputGUI::class,
				self::PROPERTY_REQUIRED => true,
				"setRows" => 6
			],
			"link" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected final function initId()/*: void*/ {
		$this->setId("tile_recommend_modal_form");
	}


	/**
	 * @inheritdoc
	 */
	protected final function initTitle()/*: void*/ {
		$this->setTitle(self::plugin()->translate("recommendation", self::LANG_MODULE, [
			$this->tile->getProperties()->getTitle()
		]));
	}
}
