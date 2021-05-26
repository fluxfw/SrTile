<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Favorites;

use ilObjUser;
use srag\Plugins\SrTile\Tile\Renderer\AbstractCollection;

/**
 * Class FavoritesCollection
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Favorites
 */
class FavoritesCollection extends AbstractCollection
{

    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * FavoritesCollection constructor
     *
     * @param ilObjUser $user
     */
    public function __construct(ilObjUser $user)
    {
        $this->user = $user;

        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    protected function initObjRefIds() /*: void*/
    {
        $this->obj_ref_ids = array_map(function (array $item) : int {
            return intval($item["child"]);
        }, self::srTile()->favorites($this->user)
            ->getFavorites());
    }
}
