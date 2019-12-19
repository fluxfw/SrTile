<?php

namespace srag\Plugins\SrTile\Utils;

use srag\Plugins\SrTile\Repository;

/**
 * Trait SrTileTrait
 *
 * @package srag\Plugins\SrTile\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait SrTileTrait
{

    /**
     * @return Repository
     */
    protected static function srTile() : Repository
    {
        return Repository::getInstance();
    }
}
