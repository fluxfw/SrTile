<?php

namespace srag\ActiveRecordConfig\SrTile\Utils;

use srag\ActiveRecordConfig\SrTile\Config\Repository as ConfigRepository;

/**
 * Trait ConfigTrait
 *
 * @package srag\ActiveRecordConfig\SrTile\Utils
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
trait ConfigTrait
{

    /**
     * @return ConfigRepository
     */
    protected static function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }
}
