<?php

namespace srag\Plugins\SrTile\ObjectLink;

use ilSelectInputGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ObjectLinkFormGUI
 *
 * @package srag\Plugins\SrTile\ObjectLink
 */
class ObjectLinkFormGUI extends PropertyFormGUI
{

    use SrTileTrait;

    const LANG_MODULE = ObjectLinksGUI::LANG_MODULE;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var ObjectLink
     */
    protected $object_link;


    /**
     * ObjectLinkFormGUI constructor
     *
     * @param ObjectLinkGUI $parent
     * @param ObjectLink    $object_link
     */
    public function __construct(ObjectLinkGUI $parent, ObjectLink $object_link)
    {
        $this->object_link = $object_link;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (!parent::storeForm()) {
            return false;
        }

        self::srTile()->objectLinks()->storeObjectLink($this->object_link);

        return true;
    }


    /**
     * @inheritDoc
     */
    protected function getValue(string $key)
    {
        switch ($key) {
            default:
                return Items::getter($this->object_link, $key);
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
                self::PROPERTY_OPTIONS  => ["" => ""] + self::srTile()->objectLinks()->getSelectableObjects($this->object_link->getGroupId(),
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
    protected function storeValue(string $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                Items::setter($this->object_link, $key, $value);
                break;
        }
    }
}
