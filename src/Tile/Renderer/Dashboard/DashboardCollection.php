<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Dashboard;

use srag\Plugins\SrTile\Tile\Renderer\Container\ContainerCollection;

/**
 * Class DashboardCollection
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Dashboard
 */
class DashboardCollection extends ContainerCollection
{

    /**
     * @inheritDoc
     */
    protected function initObjRefIds() /*: void*/
    {
        preg_match_all('/[?&]ref_id=([0-9]+)/', $this->html, $obj_ref_ids);

        if (is_array($obj_ref_ids) && count($obj_ref_ids) > 1 && is_array($obj_ref_ids[1]) && count($obj_ref_ids[1]) > 0) {

            $this->obj_ref_ids = array_unique(array_map("intval", $obj_ref_ids[1]));
        }
    }
}
