<?php

namespace srag\Notifications4Plugin\SrTile\Utils;

use srag\Notifications4Plugin\SrTile\Repository as Notifications4PluginRepository;
use srag\Notifications4Plugin\SrTile\RepositoryInterface as Notifications4PluginRepositoryInterface;

/**
 * Trait Notifications4PluginTrait
 *
 * @package srag\Notifications4Plugin\SrTile\Utils
 */
trait Notifications4PluginTrait
{

    /**
     * @return Notifications4PluginRepositoryInterface
     */
    protected static function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return Notifications4PluginRepository::getInstance();
    }
}
