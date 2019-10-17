<?php

namespace srag\Plugins\SrTile\Template;

use ilConfirmationGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TemplatesGUI
 *
 * @package           srag\Plugins\SrTile\Template
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Template\TemplatesGUI: ilSrTileConfigGUI
 */
class TemplatesGUI
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const TAB_TEMPLATES = "templates";
    const CMD_LIST_TEMPLATES = "listTemplates";
    const CMD_EDIT_TEMPLATE = "editTemplate";
    const CMD_UPDATE_TEMPLATE = "updateTemplate";
    const CMD_CONFIRM_OVERRIDE = "confirmOverride";
    const CMD_OVERRIDE = "override";
    const LANG_MODULE_TEMPLATE = "template";


    /**
     * TemplatesGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_TEMPLATES:
                    case self::CMD_EDIT_TEMPLATE:
                    case self::CMD_UPDATE_TEMPLATE:
                    case self::CMD_CONFIRM_OVERRIDE:
                    case self::CMD_OVERRIDE:
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
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_TEMPLATES);
    }


    /**
     * @param string $cmd
     *
     * @return TemplatesTableGUI
     */
    protected function getTemplatesTable(string $cmd = self::CMD_LIST_TEMPLATES) : TemplatesTableGUI
    {
        $table = new TemplatesTableGUI($this, $cmd);

        return $table;
    }


    /**
     *
     */
    protected function listTemplates()/*: void*/
    {
        $table = $this->getTemplatesTable();

        self::output()->output($table);
    }


    /**
     * @param Template $template
     *
     * @return TemplateFormGUI
     */
    protected function getTemplateFormGUI(Template $template) : TemplateFormGUI
    {
        $form = new TemplateFormGUI($this, $template);

        return $form;
    }


    /**
     *
     */
    protected function editTemplate()/*: void*/
    {
        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        $template = self::templates()->getByObjectType($object_type);

        $form = $this->getTemplateFormGUI($template);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateTemplate()/*: void*/
    {
        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        $template = self::templates()->getByObjectType($object_type);

        $form = $this->getTemplateFormGUI($template);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", TileGUI::LANG_MODULE_TILE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_CONFIRM_OVERRIDE);
    }


    /**
     *
     */
    protected function confirmOverride()/*: void*/
    {
        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("override_confirm", self::LANG_MODULE_TEMPLATE));

        $confirmation->setConfirm(self::plugin()->translate("override", self::LANG_MODULE_TEMPLATE), self::CMD_OVERRIDE);
        $confirmation->setCancel(self::plugin()->translate("not_override", self::LANG_MODULE_TEMPLATE), self::CMD_LIST_TEMPLATES);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function override()/*: void*/
    {
        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        self::templates()->overrideTilesWithObjectType($object_type);

        ilUtil::sendSuccess(self::plugin()->translate("overrided", self::LANG_MODULE_TEMPLATE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_LIST_TEMPLATES);
    }
}
