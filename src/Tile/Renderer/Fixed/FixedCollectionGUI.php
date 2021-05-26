<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Fixed;

use srag\Plugins\SrTile\Tile\Renderer\AbstractCollectionGUI;

/**
 * Class FixedCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Fixed
 */
class FixedCollectionGUI extends AbstractCollectionGUI
{

    /**
     * @inheritDoc
     */
    public function __construct(array $obj_ref_ids)
    {
        parent::__construct($obj_ref_ids);
    }
}
