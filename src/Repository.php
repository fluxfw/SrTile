<?php

namespace srag\Plugins\SrTile;

use ilObject;
use ilObjUser;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Notifications4Plugin\SrTile\RepositoryInterface as NotificationRepositoryInterface;
use srag\Notifications4Plugin\SrTile\Utils\Notifications4PluginTrait;
use srag\Plugins\SrTile\Access\Access;
use srag\Plugins\SrTile\Access\Ilias;
use srag\Plugins\SrTile\ColorThiefCache\Repository as ColorThiefCacheRepository;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\Favorite\Repository as FavoriteRepository;
use srag\Plugins\SrTile\LearningProgress\Repository as LearningProgressFilterRepository;
use srag\Plugins\SrTile\Rating\Repository as RatingRepository;
use srag\Plugins\SrTile\Recommend\Repository as RecommendRepository;
use srag\Plugins\SrTile\Template\Repository as TemplateRepository;
use srag\Plugins\SrTile\Tile\Repository as TileRepository;
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
     * @var self
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
     * @return ColorThiefCacheRepository
     */
    public function colorThiefCaches() : ColorThiefCacheRepository
    {
        return ColorThiefCacheRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(Config::TABLE_NAME, false);
        ilUtil::delDir(ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . ilSrTilePlugin::WEB_DATA_FOLDER);
        $this->colorThiefCaches()->dropTables();
        $this->favorites(self::dic()->user())->dropTables();
        $this->learningProgressFilters(self::dic()->user())->dropTables();
        $this->notifications4plugin()->dropTables();
        $this->rating(self::dic()->user())->dropTables();
        $this->recommend()->dropTables();
        $this->templates()->dropTables();
        $this->tiles()->dropTables();
    }


    /**
     * @param ilObjUser $user
     *
     * @return FavoriteRepository
     */
    public function favorites(ilObjUser $user) : FavoriteRepository
    {
        return FavoriteRepository::getInstance($user);
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
        Config::updateDB();
        $this->colorThiefCaches()->installTables();
        $this->favorites(self::dic()->user())->installTables();
        $this->learningProgressFilters(self::dic()->user())->installTables();
        $this->notifications4plugin()->installTables();
        $this->rating(self::dic()->user())->installTables();
        $this->recommend()->installTables();
        $this->templates()->installTables();
        $this->tiles()->installTables();
    }


    /**
     * @param ilObjUser $user
     *
     * @return LearningProgressFilterRepository
     */
    public function learningProgressFilters(ilObjUser $user) : LearningProgressFilterRepository
    {
        return LearningProgressFilterRepository::getInstance($user);
    }


    /**
     * @inheritDoc
     */
    public function notifications4plugin() : NotificationRepositoryInterface
    {
        return self::_notifications4plugin();
    }


    /**
     * @param ilObjUser $user
     *
     * @return RatingRepository
     */
    public function rating(ilObjUser $user) : RatingRepository
    {
        return RatingRepository::getInstance($user);
    }


    /**
     * @return RecommendRepository
     */
    public function recommend() : RecommendRepository
    {
        return RecommendRepository::getInstance();
    }


    /**
     * @return TemplateRepository
     */
    public function templates() : TemplateRepository
    {
        return TemplateRepository::getInstance();
    }


    /**
     * @return TileRepository
     */
    public function tiles() : TileRepository
    {
        return TileRepository::getInstance();
    }
}
