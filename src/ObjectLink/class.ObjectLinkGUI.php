<?php

namespace srag\Plugins\SrTile\ObjectLink;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ObjectLinkGUI
 *
 * @package           srag\Plugins\SrTile\ObjectLink
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\ObjectLink\ObjectLinkGUI: srag\Plugins\SrTile\ObjectLink\ObjectLinksGUI
 */
class ObjectLinkGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_ADD_OBJECT_LINK = "addObjectLink";
    const CMD_BACK = "back";
    const CMD_CREATE_OBJECT_LINK = "createObjectLink";
    const CMD_MOVE_OBJECT_LINK_DOWN = "moveObjectLinkDown";
    const CMD_MOVE_OBJECT_LINK_UP = "moveObjectLinkUp";
    const CMD_REMOVE_OBJECT_LINK = "removeObjectLink";
    const GET_PARAM_OBJ_REF_ID = "obj_ref_id";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const TAB_EDIT_OBJECT_LINK = "edit_object_link";
    /**
     * @var ObjectLink
     */
    protected $object_link;
    /**
     * @var ObjectLinksGUI
     */
    protected $parent;


    /**
     * ObjectLinkGUI constructor
     *
     * @param ObjectLinksGUI $parent
     */
    public function __construct(ObjectLinksGUI $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->object_link = self::srTile()->objectLinks()->getObjectLink($this->parent->getGroup()->getGroupId(), intval(filter_input(INPUT_GET, self::GET_PARAM_OBJ_REF_ID)));

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_OBJECT_LINK:
                    case self::CMD_BACK:
                    case self::CMD_CREATE_OBJECT_LINK:
                    case self::CMD_MOVE_OBJECT_LINK_DOWN:
                    case self::CMD_MOVE_OBJECT_LINK_UP:
                    case self::CMD_REMOVE_OBJECT_LINK:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return ObjectLinksGUI
     */
    public function getParent() : ObjectLinksGUI
    {
        return $this->parent;
    }


    /**
     *
     */
    protected function addObjectLink()/*: void*/
    {
        $form = self::srTile()->objectLinks()->factory()->newFormInstance($this, $this->object_link);

        self::output()->output($form, true);
    }


    /**
     *
     */
    protected function back()/*: void*/
    {
        self::dic()->ctrl()->redirectByClass(ObjectLinksGUI::class, ObjectLinksGUI::CMD_LIST_OBJECT_LINKS);
    }


    /**
     *
     */
    protected function createObjectLink()/*: void*/
    {
        $form = self::srTile()->objectLinks()->factory()->newFormInstance($this, $this->object_link);

        if (!$form->storeForm()) {
            self::output()->output($form, true);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("added_object_link", ObjectLinksGUI::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function moveObjectLinkDown()
    {
        self::srTile()->objectLinks()->moveObjectLinkDown($this->object_link);

        exit;
    }


    /**
     *
     */
    protected function moveObjectLinkUp()
    {
        self::srTile()->objectLinks()->moveObjectLinkUp($this->object_link);

        exit;
    }


    /**
     *
     */
    protected function removeObjectLink()/*: void*/
    {
        if ($this->object_link->getObjRefId() !== $this->parent->getParent()->getTile()->getObjRefId()) {
            self::srTile()->objectLinks()->deleteObjectLink($this->object_link);

            ilUtil::sendSuccess(self::plugin()->translate("removed_object_link", ObjectLinksGUI::LANG_MODULE), true);
        }

        self::dic()->ctrl()->redirect($this, self::CMD_BACK);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("object_links", ObjectLinksGUI::LANG_MODULE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK));

        if ($this->object_link === null) {
            $this->object_link = self::srTile()->objectLinks()->factory()->newObjectLinkInstance();
            $this->object_link->setGroupId($this->parent->getGroup()->getGroupId());

            self::dic()->tabs()->addTab(self::TAB_EDIT_OBJECT_LINK, self::plugin()->translate("add_object_link", ObjectLinksGUI::LANG_MODULE), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_ADD_OBJECT_LINK));
        }
    }
}
