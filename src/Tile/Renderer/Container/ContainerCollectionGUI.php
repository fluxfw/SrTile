<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Container;

use srag\Plugins\SrTile\Tile\Renderer\AbstractCollectionGUI;

/**
 * Class ContainerCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Container
 */
class ContainerCollectionGUI extends AbstractCollectionGUI
{

    /**
     * @inheritDoc
     */
    public function __construct(string $html)
    {
        parent::__construct($html);
    }
}
