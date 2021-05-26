<?php

namespace srag\Plugins\SrTile\Utils;

use srag\Plugins\SrTile\Repository;

/**
 * Trait SrTileTrait
 *
 * @package srag\Plugins\SrTile\Utils
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
