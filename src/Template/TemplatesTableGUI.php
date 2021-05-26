<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrTile\TableGUI\TableGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TemplatesTableGUI
 *
 * @package srag\Plugins\SrTile\Template
 */
class TemplatesTableGUI extends TableGUI
{

    use SrTileTrait;

    const LANG_MODULE = TemplatesConfigGUI::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;


    /**
     * TemplatesTableGUI constructor
     *
     * @param TemplatesConfigGUI $parent
     * @param string             $parent_cmd
     */
    public function __construct(TemplatesConfigGUI $parent, string $parent_cmd)
    {
        parent::__construct($parent, $parent_cmd);
    }


    /**
     * @inheritDoc
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
     * @param Template $template
     */
    protected function fillRow(/*Template*/ $template)/*: void*/
    {
        self::dic()->ctrl()->setParameterByClass(TemplateConfigGUI::class, TemplateConfigGUI::GET_PARAM_OBJECT_TYPE, $template->getObjectType());

        parent::fillRow($template);

        $this->tpl->setVariable("COLUMN", self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard([
            self::dic()->ui()->factory()->link()->standard($this->txt("edit_template"), self::dic()->ctrl()
                ->getLinkTargetByClass(TemplateConfigGUI::class, TemplateConfigGUI::CMD_EDIT_TEMPLATE))
        ])->withLabel($this->txt("actions"))));
    }


    /**
     * @inheritDoc
     *
     * @param Template $template
     */
    protected function getColumnValue(string $column, /*Template*/ $template, int $format = self::DEFAULT_FORMAT) : string
    {
        switch ($column) {
            case "object_type":
                $column = htmlspecialchars($template->_getTitle());
                break;

            default:
                $column = htmlspecialchars(Items::getter($template, $column));
                break;
        }

        return strval($column);
    }


    /**
     * @inheritDoc
     */
    protected function initColumns()/*: void*/
    {
        parent::initColumns();

        $this->addColumn($this->txt("actions"));
    }


    /**
     * @inheritDoc
     */
    protected function initData()/*: void*/
    {
        $this->setExternalSegmentation(true);
        $this->setExternalSorting(true);

        $this->setData(self::srTile()->templates()->getArray());
    }


    /**
     * @inheritDoc
     */
    protected function initFilterFields()/*: void*/
    {
        $this->filter_fields = [];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId(ilSrTilePlugin::PLUGIN_ID . "_templates");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("templates"));
    }
}
