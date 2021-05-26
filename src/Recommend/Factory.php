<?php

namespace srag\Plugins\SrTile\Recommend;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrTile\Recommend
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
     * @param RecommendGUI $parent
     * @param Recommend    $recommend
     *
     * @return RecommendFormGUI
     */
    public function newFormInstance(RecommendGUI $parent, Recommend $recommend) : RecommendFormGUI
    {
        $form = new RecommendFormGUI($parent, $recommend);

        return $form;
    }


    /**
     * @param Tile $tile
     *
     * @return Recommend
     */
    public function newInstance(Tile $tile) : Recommend
    {
        $recommend = new Recommend($tile);

        return $recommend;
    }
}
