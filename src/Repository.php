<?php

namespace srag\Plugins\SrTile;

use ilObject;
use ilObjUser;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Notifications4Plugin\SrTile\RepositoryInterface as Notifications4PluginRepositoryInterface;
use srag\Notifications4Plugin\SrTile\Utils\Notifications4PluginTrait;
use srag\Plugins\SrTile\Access\Access;
use srag\Plugins\SrTile\Access\Ilias;
use srag\Plugins\SrTile\ColorThiefCache\Repository as ColorThiefCachesRepository;
use srag\Plugins\SrTile\Config\Repository as ConfigRepository;
use srag\Plugins\SrTile\Favorite\Repository as FavoritesRepository;
use srag\Plugins\SrTile\LearningProgress\Repository as LearningProgressFiltersRepository;
use srag\Plugins\SrTile\ObjectLink\Repository as ObjectLinksRepository;
use srag\Plugins\SrTile\OnlineStatus\Repository as OnlineStatusRepository;
use srag\Plugins\SrTile\Rating\Repository as RatingsRepository;
use srag\Plugins\SrTile\Recommend\Repository as RecommendsRepository;
use srag\Plugins\SrTile\Template\Repository as TemplatesRepository;
use srag\Plugins\SrTile\Tile\Repository as TilesRepository;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use SrTileTrait;
    use Notifications4PluginTrait {
        notifications4plugin as protected _notifications4plugin;
    }
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
        $this->notifications4plugin()->withTableNamePrefix("ui_uihk_" . ilSrTilePlugin::PLUGIN_ID)->withPlugin(self::plugin())->withPlaceholderTypes([
            "link"    => "string",
            "message" => "string",
            "object"  => "object " . ilObject::class,
            "user"    => "object " . ilObjUser::class
        ]);
    }


    /**
     * @return Access
     */
    public function access() : Access
    {
        return Access::getInstance();
    }


    /**
     * @return ColorThiefCachesRepository
     */
    public function colorThiefCaches() : ColorThiefCachesRepository
    {
        return ColorThiefCachesRepository::getInstance();
    }


    /**
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables()/*:void*/
    {
        ilUtil::delDir(ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . ilSrTilePlugin::WEB_DATA_FOLDER);
        $this->config()->dropTables();
        $this->colorThiefCaches()->dropTables();
        $this->favorites(self::dic()->user())->dropTables();
        $this->learningProgressFilters(self::dic()->user())->dropTables();
        $this->notifications4plugin()->dropTables();
        $this->objectLinks()->dropTables();
        $this->onlineStatus()->dropTables();
        $this->ratings(self::dic()->user())->dropTables();
        $this->recommends()->dropTables();
        $this->templates()->dropTables();
        $this->tiles()->dropTables();
    }


    /**
     * @param ilObjUser $user
     *
     * @return FavoritesRepository
     */
    public function favorites(ilObjUser $user) : FavoritesRepository
    {
        return FavoritesRepository::getInstance($user);
    }


    /**
     * @return Ilias
     */
    public function ilias() : Ilias
    {
        return Ilias::getInstance();
    }


    /**
     *
     */
    public function installTables()/*:void*/
    {
        $this->config()->installTables();
        $this->colorThiefCaches()->installTables();
        $this->favorites(self::dic()->user())->installTables();
        $this->learningProgressFilters(self::dic()->user())->installTables();
        $this->notifications4plugin()->installTables();
        $this->objectLinks()->installTables();
        $this->onlineStatus()->installTables();
        $this->ratings(self::dic()->user())->installTables();
        $this->recommends()->installTables();
        $this->templates()->installTables();
        $this->tiles()->installTables();
    }


    /**
     * @param ilObjUser $user
     *
     * @return LearningProgressFiltersRepository
     */
    public function learningProgressFilters(ilObjUser $user) : LearningProgressFiltersRepository
    {
        return LearningProgressFiltersRepository::getInstance($user);
    }


    /**
     * @inheritDoc
     */
    public function notifications4plugin() : Notifications4PluginRepositoryInterface
    {
        return self::_notifications4plugin();
    }


    /**
     * @return ObjectLinksRepository
     */
    public function objectLinks() : ObjectLinksRepository
    {
        return ObjectLinksRepository::getInstance();
    }


    /**
     * @return OnlineStatusRepository
     */
    public function onlineStatus() : OnlineStatusRepository
    {
        return OnlineStatusRepository::getInstance();
    }


    /**
     * @param ilObjUser $user
     *
     * @return RatingsRepository
     */
    public function ratings(ilObjUser $user) : RatingsRepository
    {
        return RatingsRepository::getInstance($user);
    }


    /**
     * @return RecommendsRepository
     */
    public function recommends() : RecommendsRepository
    {
        return RecommendsRepository::getInstance();
    }


    /**
     * @return TemplatesRepository
     */
    public function templates() : TemplatesRepository
    {
        return TemplatesRepository::getInstance();
    }


    /**
     * @return TilesRepository
     */
    public function tiles() : TilesRepository
    {
        return TilesRepository::getInstance();
    }
}
