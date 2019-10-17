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
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(TemplatesConfigGUI::CMD_UPDATE_TEMPLATE, $this->txt("save"));

        $this->addCommandButton(TemplatesConfigGUI::CMD_LIST_TEMPLATES, $this->txt("cancel"));
    }
}
