<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use srag\Plugins\SrTile\ObjectLink\ObjectLink;

/**
 * Interface SingleGUIInterface
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
interface SingleGUIInterface
{

    /**
     * @return string
     */
    public function getActionAsyncUrl() : string;

    /**
     * @param ObjectLink[] $object_links
     *
     * @return string
     */
    public function getActions(array $object_links = []) : string;

    /**
     * @return string
     */
    public function render() : string;
}
