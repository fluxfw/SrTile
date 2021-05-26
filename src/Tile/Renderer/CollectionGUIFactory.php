<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Renderer\Container\ContainerCollectionGUI;
use srag\Plugins\SrTile\Tile\Renderer\Dashboard\DashboardCollectionGUI;
use srag\Plugins\SrTile\Tile\Renderer\Favorites\FavoritesCollectionGUI;
use srag\Plugins\SrTile\Tile\Renderer\Fixed\FixedCollectionGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class CollectionGUIFactory
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 */
final class CollectionGUIFactory
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * CollectionGUIFactory constructor
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
     * @param string $html
     *
     * @return ContainerCollectionGUI
     */
    public function container(string $html) : ContainerCollectionGUI
    {
        $collection_gui = new ContainerCollectionGUI($html);

        return $collection_gui;
    }


    /**
     * @param string $html
     *
     * @return DashboardCollectionGUI
     */
    public function dashboard(string $html) : DashboardCollectionGUI
    {
        $collection_gui = new DashboardCollectionGUI($html);

        return $collection_gui;
    }


    /**
     * @param ilObjUser $user
     *
     * @return FavoritesCollectionGUI
     */
    public function favorites(ilObjUser $user) : FavoritesCollectionGUI
    {
        $collection_gui = new FavoritesCollectionGUI($user);

        return $collection_gui;
    }


    /**
     * @param array $obj_ref_ids
     *
     * @return FixedCollectionGUI
     */
    public function fixed(array $obj_ref_ids) : FixedCollectionGUI
    {
        $collection_gui = new FixedCollectionGUI($obj_ref_ids);

        return $collection_gui;
    }
}
