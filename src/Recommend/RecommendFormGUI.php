<?php

namespace srag\Plugins\SrTile\Recommend;

use ilEMailInputGUI;
use ilNonEditableValueGUI;
use ilSrTilePlugin;
use ilTextAreaInputGUI;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;
use SrTileRecommendGUI;

/**
 * Class RecommendFormGUI
 *
 * @package srag\Plugins\SrTile\Recommend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommendFormGUI extends PropertyFormGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const LANG_MODULE = SrTileRecommendGUI::LANG_MODULE_RECOMMENDATION;
	/**
	 * @var Recommend
	 */
	protected $recommend;
	/**
	 * @var Tile
	 */
	protected $tile;


	/**
	 * RecommendFormGUI constructor
	 *
	 * @param SrTileRecommendGUI $parent
	 * @param Tile               $tile
	 */
	public function __construct(SrTileRecommendGUI $parent, Tile $tile) {
		$this->tile = $tile;
		$this->recommend = new Recommend($this->tile);

		parent::__construct($parent);
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			default:
				if (method_exists($this->recommend, $method = "get" . $this->strToCamelCase($key))) {
					return $this->recommend->{$method}($key);
				}
				break;
		}

		return NULL;
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
		$this->addCommandButton(SrTileRecommendGUI::CMD_NEW_RECOMMEND, $this->txt("submit"), "tile_recommend_modal_submit");

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


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			default:
				if (method_exists($this->recommend, $method = "set" . $this->strToCamelCase($key))) {
					$this->recommend->{$method}($value);
				}
				break;
		}
	}


	/**
	 * @inheritdoc
	 */
	public function storeForm()/*: void*/ {
		if (!parent::storeForm()) {
			return false;
		}

		return true;
	}


	/**
	 * @return Recommend
	 */
	public function getRecommend(): Recommend {
		return $this->recommend;
	}


	/**
	 * @param string $string
	 *
	 * @return string
	 */
	protected function strToCamelCase($string): string {
		return str_replace("_", "", ucwords($string, "_"));
	}
}
