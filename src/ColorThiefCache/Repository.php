<?php

namespace srag\Plugins\SrTile\ColorThiefCache;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\ColorThiefCache
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use SrTileTrait;
    use DICTrait;

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
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @param string $image_path
     */
    public function delete(string $image_path)/*: void*/
    {
        /**
         * @var ColorThiefCache $colorThiefCache
         */

        $colorThiefCache = ColorThiefCache::where([
            "image_path" => $image_path
        ])->first();

        if ($colorThiefCache !== null) {
            $this->deleteColorThiefCache($colorThiefCache);
        }
    }


    /**
     * @param ColorThiefCache $colorThiefCache
     */
    protected function deleteColorThiefCache(ColorThiefCache $colorThiefCache)/*:void*/
    {
        $colorThiefCache->delete();
    }


    /**
     * @param string $image_path
     *
     * @return ColorThiefCache
     */
    public function getColorThiefCache(string $image_path) : ColorThiefCache
    {
        /**
         * @var ColorThiefCache $colorThiefCache
         */

        $colorThiefCache = ColorThiefCache::where([
            "image_path" => $image_path
        ])->first();

        if ($colorThiefCache === null) {
            $colorThiefCache = $this->factory()->newInstance();

            $colorThiefCache->setImagePath($image_path);
        }

        return $colorThiefCache;
    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(ColorThiefCache::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        ColorThiefCache::updateDB();
    }


    /**
     * @param ColorThiefCache $colorThiefCache
     */
    public function storeColorThiefCache(ColorThiefCache $colorThiefCache)/*:void*/
    {
        $colorThiefCache->store();
    }
}
