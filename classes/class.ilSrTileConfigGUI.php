<?php

require_once __DIR__ . "/../vendor/autoload.php";

use srag\ActiveRecordConfig\SrTile\ActiveRecordConfigGUI;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Notification\Ctrl\Notifications4PluginCtrl;
use srag\Plugins\SrTile\Template\Template;
use srag\Plugins\SrTile\Template\TemplateFormGUI;
use srag\Plugins\SrTile\Template\TemplatesTableGUI;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ilSrTileConfigGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTileConfigGUI extends ActiveRecordConfigGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE_TEMPLATE = "template";
    const TAB_TEMPLATES = "templates";
    const CMD_EDIT_TEMPLATE = "editTemplate";
    const CMD_UPDATE_TEMPLATE = "updateTemplate";
    const CMD_CONFIRM_OVERRIDE = "confirmOverride";
    const CMD_OVERRIDE = "override";
    /**
     * @var array
     */
    protected static $tabs
        = [
            self::TAB_CONFIGURATION                     => ConfigFormGUI::class,
            self::TAB_TEMPLATES                         => TemplatesTableGUI::class,
            Notifications4PluginCtrl::TAB_NOTIFICATIONS => [
                Notifications4PluginCtrl::class,
                Notifications4PluginCtrl::CMD_LIST_NOTIFICATIONS
            ]
        ];
    /**
     * @var array
     */
    protected static $custom_commands
        = [
            self::CMD_EDIT_TEMPLATE,
            self::CMD_UPDATE_TEMPLATE,
            self::CMD_CONFIRM_OVERRIDE,
            self::CMD_OVERRIDE
        ];


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
        self::dic()->tabs()->activateTab(self::TAB_TEMPLATES);

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
        self::dic()->tabs()->activateTab(self::TAB_TEMPLATES);

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
        self::dic()->tabs()->activateTab(self::TAB_TEMPLATES);

        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("override_confirm", self::LANG_MODULE_TEMPLATE));

        $confirmation->setConfirm(self::plugin()->translate("override", self::LANG_MODULE_TEMPLATE), self::CMD_OVERRIDE);
        $confirmation->setCancel(self::plugin()->translate("not_override", self::LANG_MODULE_TEMPLATE), $this->getCmdForTab(self::TAB_TEMPLATES));

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function override()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_TEMPLATES);

        $object_type = filter_input(INPUT_GET, "srtile_object_type");

        self::dic()->ctrl()->setParameterByClass(self::class, "srtile_object_type", $object_type);

        self::templates()->overrideTilesWithObjectType($object_type);

        ilUtil::sendSuccess(self::plugin()->translate("overrided", self::LANG_MODULE_TEMPLATE), true);

        $this->redirectToTab(self::TAB_TEMPLATES);
    }
}
