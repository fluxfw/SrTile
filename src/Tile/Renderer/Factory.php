<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return CollectionGUIFactory
     */
    public function newCollectionGUIInstance() : CollectionGUIFactory
    {
        return CollectionGUIFactory::getInstance();
    }


    /**
     * @param CollectionGUIInterface $collection_gui
     * @param mixed                  $param
     *
     * @return CollectionInterface
     */
    public function newCollectionInstance(CollectionGUIInterface $collection_gui, $param) : CollectionInterface
    {
        $class = get_class($collection_gui);

        $class = str_replace("GUI", "", $class);

        $collection = new $class($param);

        return $collection;
    }


    /**
     * @param CollectionGUIInterface $collection_gui
     * @param Tile                   $tile
     *
     * @return SingleGUIInterface
     */
    public function newSingleGUIInstance(CollectionGUIInterface $collection_gui, Tile $tile) : SingleGUIInterface
    {
        $class = get_class($collection_gui);

        $class = str_replace("Collection", "Single", $class);

        $single_ui = new $class($tile);

        return $single_ui;
    }
}
