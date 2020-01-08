<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

/**
 * Interface SingleGUIInterface
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
interface SingleGUIInterface
{

    /**
     * @return string
     */
    public function render() : string;


    /**
     * @return string
     */
    public function getActions() : string;


    /**
     * @return string
     */
    public function getActionAsyncUrl() : string;
}
