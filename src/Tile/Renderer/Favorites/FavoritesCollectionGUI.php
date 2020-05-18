<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Favorites;

use ilObjUser;
use srag\Plugins\SrTile\Tile\Renderer\AbstractCollectionGUI;

/**
 * Class FavoritesCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Favorites
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
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
