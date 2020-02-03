<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Fixed;

use srag\Plugins\SrTile\Tile\Renderer\AbstractCollection;

/**
 * Class FixedcCollection
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Fixed
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class FixedCollection extends AbstractCollection
{

    /**
     * FixedCollection constructor
     *
     * @param array $obj_ref_ids
     */
    public function __construct(array $obj_ref_ids)
    {
        $this->obj_ref_ids = $obj_ref_ids;

        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    protected function initObjRefIds() /*: void*/
    {

    }
}
