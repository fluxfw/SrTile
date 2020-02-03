<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Container;

use srag\Plugins\SrTile\Tile\Renderer\AbstractCollectionGUI;

/**
 * Class ContainerCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Container
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
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
