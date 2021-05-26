<?php

namespace srag\DIC\SrTile\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrTile\Plugin
 */
interface Pluginable
{

    /**
     * @return PluginInterface
     */
    public function getPlugin() : PluginInterface;


    /**
     * @param PluginInterface $plugin
     *
     * @return static
     */
    public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
