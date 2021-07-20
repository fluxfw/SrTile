<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use srag\Plugins\srTile\Tile\Tile;

/**
 * Interface CollectionInterface
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 */
interface CollectionInterface
{

    /**
     * @param Tile $tile
     */
    public function addTile(Tile $tile) : void;


    /**
     * @return Tile[]
     */
    public function getTiles() : array;


    /**
     * @param int $tile_id
     */
    public function removeTile(int $tile_id) : void;
}
