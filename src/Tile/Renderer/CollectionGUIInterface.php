<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

/**
 * Interface CollectionGUIInterface
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
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
