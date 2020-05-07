<?php

namespace srag\Plugins\SrTile\Template;

use ilConfirmationGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TemplateConfigGUI
 *
 * @package           srag\Plugins\SrTile\Template
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Template\TemplateConfigGUI: srag\Plugins\SrTile\Template\TemplatesConfigGUI
 */
class TemplateConfigGUI
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CMD_BACK = "back";
    const CMD_CONFIRM_OVERRIDE = "confirmOverride";
    const CMD_EDIT_TEMPLATE = "editTemplate";
    const CMD_OVERRIDE = "override";
    const CMD_UPDATE_TEMPLATE = "updateTemplate";
    const GET_PARAM_OBJECT_TYPE = "object_type";
    const TAB_EDIT_TEMPLATE = "edit_template";
    /**
     * @var Template
     */
    protected $template;


    /**
     * TemplateConfigGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->template = self::srTile()->templates()->getByObjectType(strval(filter_input(INPUT_GET, self::GET_PARAM_OBJECT_TYPE)));

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_OBJECT_TYPE);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_BACK:
                    case self::CMD_CONFIRM_OVERRIDE:
                    case self::CMD_EDIT_TEMPLATE:
                    case self::CMD_OVERRIDE:
                    case self::CMD_UPDATE_TEMPLATE:
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
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("templates", TemplatesConfigGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        self::dic()->tabs()->addTab(self::TAB_EDIT_TEMPLATE, self::plugin()->translate("edit_template", TemplatesConfigGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_EDIT_TEMPLATE));
    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(TemplatesConfigGUI::class, TemplatesConfigGUI::CMD_LIST_TEMPLATES);
    }


    /**
     *
     */
    protected function editTemplate()/*: void*/
    {
        $form = self::srTile()->templates()->factory()->newFormInstance($this, $this->template);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function updateTemplate()/*: void*/
    {
        $form = self::srTile()->templates()->factory()->newFormInstance($this, $this->template);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", TileGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_CONFIRM_OVERRIDE);
    }


    /**
     *
     */
    protected function confirmOverride()/*: void*/
    {
        $confirmation = new ilConfirmationGUI();

        $confirmation->setFormAction(self::dic()->ctrl()->getFormAction($this));

        $confirmation->setHeaderText(self::plugin()->translate("override_confirm", TemplatesConfigGUI::LANG_MODULE));

        $confirmation->setConfirm(self::plugin()->translate("override", TemplatesConfigGUI::LANG_MODULE), self::CMD_OVERRIDE);
        $confirmation->setCancel(self::plugin()->translate("not_override", TemplatesConfigGUI::LANG_MODULE), self::CMD_BACK);

        self::output()->output($confirmation);
    }


    /**
     *
     */
    protected function override()/*: void*/
    {
        self::srTile()->templates()->overrideTilesWithObjectType($this->template->getObjectType());

        ilUtil::sendSuccess(self::plugin()->translate("overrided", TemplatesConfigGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }
}
