<?php

namespace srag\DIC\SrTile\Plugin;

/**
 * Interface Pluginable
 *
 * @package srag\DIC\SrTile\Plugin
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
interface Pluginable {

	/**
	 * @return PluginInterface
	 */
	public function getPlugin(): PluginInterface;


	/**
	 * @param PluginInterface $plugin
	 *
	 * @return static
	 */
	public function withPlugin(PluginInterface $plugin)/*: static*/ ;
}
