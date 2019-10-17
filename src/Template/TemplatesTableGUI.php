<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\TableGUI\TableGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TemplatesTableGUI
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TemplatesTableGUI extends TableGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE = TemplatesGUI::LANG_MODULE_TEMPLATE;


    /**
     * TemplatesTableGUI constructor
     *
     * @param TemplatesGUI $parent
     * @param string       $parent_cmd
     */
    public function __construct(TemplatesGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritdoc
     */
    protected function getColumnValue(/*string*/
        $column, /*array*/
        $row, /*int*/
        $format = self::DEFAULT_FORMAT
    ) : string {
        switch ($column) {
            case "object_type":
                $column = $row["template"]->_getTitle();
                break;

            default:
                $column = $row[$column];
                break;
        }

        return strval($column);
    }


    /**
     * @inheritdoc
     */
    public function getSelectableColumns2() : array
    {
        $columns = [
            "object_type" => [
                "id"      => "object_type",
                "default" => true,
                "sort"    => true
            ]
        ];

        return $columns;
    }


    /**
     * @inheritdoc
     */
    protected function initColumns()/*: void*/
    {
        parent::initColumns();

        $this->addColumn($this->txt("actions"));
    }


    /**
     * @inheritdoc
     */
    protected function initData()/*: void*/
    {
        $this->setData(self::templates()->getArray());
    }


    /**
     * @inheritdoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [];
    }


    /**
     * @inheritdoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("srtile_templates");
    }


    /**
     * @inheritdoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("templates"));
    }


    /**
     * @param array $row
     */
    protected function fillRow(/*array*/
        $row
    )/*: void*/
    {
        self::dic()->ctrl()->setParameter($this->parent_obj, "srtile_object_type", $row["template"]->getObjectType());

        parent::fillRow($row);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->button()->shy($this->txt("edit_template"), self::dic()->ctrl()
                ->getLinkTarget($this->parent_obj, TemplatesGUI::CMD_EDIT_TEMPLATE))
        ])->withLabel($this->txt("actions"))));
    }
}
