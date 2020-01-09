<?php

namespace srag\Plugins\SrTile\ObjectLink;

use ilSelectInputGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ObjectLinkFormGUI
 *
 * @package srag\Plugins\SrTile\ObjectLink
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ObjectLinkFormGUI extends ObjectPropertyFormGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE = ObjectLinksGUI::LANG_MODULE;
    /**
     * @var ObjectLink
     */
    protected $object;


    /**
     * ObjectLinkFormGUI constructor
     *
     * @param ObjectLinkGUI $parent
     * @param ObjectLink    $object
     */
    public function __construct(ObjectLinkGUI $parent, ObjectLink $object)
    {
        parent::__construct($parent, $object, false);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return parent::getValue($key);
        }
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(ObjectLinkGUI::CMD_CREATE_OBJECT_LINK, $this->txt("add"));
        $this->addCommandButton(ObjectLinkGUI::CMD_BACK, $this->txt("cancel"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "obj_ref_id" => [
                self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                self::PROPERTY_OPTIONS  => ["" => ""] + self::srTile()->objectLinks()->getSelectableObjects($this->object->getGroupId(),
                        $this->parent->getParent()->getParent()->getTile()->getObjRefId()),
                "setTitle"              => $this->txt("object")
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {

    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->txt("add_object_link"));
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                parent::storeValue($key, $value);
                break;
        }
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::srTile()->objectLinks()->storeObjectLink($this->object);

        return true;
    }
}
