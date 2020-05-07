<?php

namespace srag\Plugins\SrTile\Recommend;

use ilEMailInputGUI;
use ilNonEditableValueGUI;
use ilSrTilePlugin;
use ilTextAreaInputGUI;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class RecommendFormGUI
 *
 * @package srag\Plugins\SrTile\Recommend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class RecommendFormGUI extends PropertyFormGUI
{

    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE = RecommendGUI::LANG_MODULE;
    /**
     * @var Recommend
     */
    protected $recommend;


    /**
     * RecommendFormGUI constructor
     *
     * @param RecommendGUI $parent
     * @param Recommend    $recommend
     */
    public function __construct(RecommendGUI $parent, Recommend $recommend)
    {
        $this->recommend = $recommend;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            default:
                return Items::getter($this->recommend, $key);
        }
    }


    /**
     * @inheritDoc
     */
    protected final function initAction()/*: void*/
    {
        $this->setFormAction(self::dic()->ctrl()->getFormAction($this->parent, "", "", true));
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(RecommendGUI::CMD_NEW_RECOMMEND, $this->txt("submit"), "tile_recommend_modal_submit");

        $this->addCommandButton("", $this->txt("cancel"), "tile_recommend_modal_cancel");

        $this->setShowTopButtons(false);
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "recommended_to" => [
                self::PROPERTY_CLASS    => ilEMailInputGUI::class,
                self::PROPERTY_REQUIRED => true
            ],
            "message"        => [
                self::PROPERTY_CLASS    => ilTextAreaInputGUI::class,
                self::PROPERTY_REQUIRED => true,
                "setRows"               => 6
            ],
            "link"           => [
                self::PROPERTY_CLASS => ilNonEditableValueGUI::class
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected final function initId()/*: void*/
    {
        $this->setId("tile_recommend_modal_form");
    }


    /**
     * @inheritDoc
     */
    protected final function initTitle()/*: void*/
    {
        $this->setTitle(self::plugin()->translate("recommendation", self::LANG_MODULE, [
            $this->recommend->getTile()->_getTitle()
        ]));
    }


    /**
     * @inheritdoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            default:
                Items::setter($this->recommend, $key, $value);
                break;
        }
    }
}
