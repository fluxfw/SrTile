<?php

namespace srag\Plugins\SrTile\Recommend;

/**
 * Class SuccessFormGUI
 *
 * @package srag\Plugins\SrTile\Recommend
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class SuccessFormGUI extends RecommendFormGUI
{

    /**
     * @inheritdoc
     */
    protected function getValue(/*string*/
        $key
    )/*: void*/
    {

    }


    /**
     * @inheritdoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton("", $this->txt("close"), "tile_recommend_modal_cancel");

        $this->setShowTopButtons(false);
    }


    /**
     * @inheritdoc
     */
    protected function initFields()/*: void*/
    {

    }


    /**
     * @inheritdoc
     */
    public function storeForm()/*: bool*/
    {
        return false;
    }


    /**
     * @inheritdoc
     */
    protected function storeValue(/*string*/
        $key,
        $value
    )/*: void*/
    {

    }
}
