<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

/**
 * Interface CollectionGUIInterface
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
interface CollectionGUIInterface
{

    /**
     *
     */
    public function hideOriginalRowsOfTiles() /*: void*/ ;

    /**
     * @return string
     */
    public function render() : string;
}
