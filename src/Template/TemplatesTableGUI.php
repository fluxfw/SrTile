<?php

namespace srag\Plugins\SrTile\Template;

use ilAdvancedSelectionListGUI;
use ilSrTileConfigGUI;
use ilSrTilePlugin;
use srag\ActiveRecordConfig\SrTile\ActiveRecordConfigTableGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TemplatesTableGUI
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TemplatesTableGUI extends ActiveRecordConfigTableGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


	/**
	 * @inheritdoc
	 */
	protected function getColumnValue(/*string*/
		$column, /*array*/
		$row, /*bool*/
		$raw_export = false): string {
		switch ($column) {
			default:
				$column = $row[$column];
				break;
		}

		return strval($column);
	}


	/**
	 * @inheritdoc
	 */
	public function getSelectableColumns2(): array {
		$columns = [
			"object_type" => [
				"id" => "object_type",
				"default" => true,
				"sort" => true
			]
		];

		return $columns;
	}


	/**
	 * @inheritdoc
	 */
	protected function initColumns()/*: void*/ {
		parent::initColumns();

		$this->addColumn($this->txt("actions"));
	}


	/**
	 * @inheritdoc
	 */
	protected function initData()/*: void*/ {
		$this->setData(self::templates()->getArray());
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("srtile_templates");
	}


	/**
	 * @param array $row
	 */
	protected function fillRow(/*array*/
		$row)/*: void*/ {
		self::dic()->ctrl()->setParameter($this->parent_obj, "srtile_object_type", $row["object_type"]);
		$edit_template_link = self::dic()->ctrl()->getLinkTarget($this->parent_obj, ilSrTileConfigGUI::CMD_EDIT_TEMPLATE);

		parent::fillRow($row);

		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->txt("actions"));

		$actions->addItem($this->txt("edit_template"), "", $edit_template_link);

		$this->tpl->setVariable("COLUMN", self::output()->getHTML($actions));
	}
}
