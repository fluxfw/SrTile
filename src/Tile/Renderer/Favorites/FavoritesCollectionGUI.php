<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Favorites;

use ilObjUser;
use srag\Plugins\SrTile\Tile\Renderer\AbstractCollectionGUI;

/**
 * Class FavoritesCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Favorites
 */
class FavoritesCollectionGUI extends AbstractCollectionGUI
{

    /**
     * @inheritDoc
     */
    public function __construct(ilObjUser $user)
    {
        parent::__construct($user);
    }
}
