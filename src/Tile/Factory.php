<?php

namespace srag\Plugins\SrTile\Tile;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Template\TemplatesConfigGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrTile\Tile
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
     * Factory constructor
     */
    private function __construct()
    {

    }


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
     * @param TileGUI|TemplatesConfigGUI $parent
     * @param Tile                       $tile
     *
     * @return TileFormGUI
     */
    public function newFormInstance(TileGUI $parent, Tile $tile) : TileFormGUI
    {
        $form = new TileFormGUI($parent, $tile);

        return $form;
    }


    /**
     * @return Tile
     */
    public function newInstance() : Tile
    {
        $tile = new Tile();

        return $tile;
    }
}
