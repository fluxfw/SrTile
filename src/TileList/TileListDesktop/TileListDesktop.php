<?php

namespace srag\Plugins\SrTile\TileList\TileListDesktop;

use ilObjUser;
use srag\Plugins\SrTile\TileList\TileListAbstract;

/**
 * Class TileListDesktop
 *
 * @package srag\Plugins\SrTile\TileList\TileListDesktop
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListDesktop extends TileListAbstract
{

    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * TileListDesktop constructor
     *
     * @param ilObjUser $user
     */
    protected function __construct(ilObjUser $user) /*: void*/
    {
        $this->user = $user;

        parent::__construct();
    }


    /**
     * @inheritdoc
     */
    protected function initObjRefIds() /*: void*/
    {
        $this->obj_ref_ids = array_map(function (array $item) : int { return intval($item["child"]); }, self::ilias()->favorites($this->user)
            ->getFavorites());
    }
}
