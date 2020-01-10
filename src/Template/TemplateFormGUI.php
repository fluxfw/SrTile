<?php

namespace srag\Plugins\SrTile\Template;

use srag\Plugins\SrTile\Tile\TileFormGUI;

/**
 * Class TemplateFormGUI
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TemplateFormGUI extends TileFormGUI
{

    /**
     * @inheritDoc
     */
    public function __construct(TemplateConfigGUI $parent, Template $object)
    {
        parent::__construct($parent, $object);
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(TemplateConfigGUI::CMD_UPDATE_TEMPLATE, $this->txt("save"));

        $this->addCommandButton(TemplateConfigGUI::CMD_BACK, $this->txt("cancel"));
    }
}
