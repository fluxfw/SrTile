<?php

namespace srag\Plugins\SrTile\Favorite;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\Favorite
 */
final class Repository
{

    use SrTileTrait;
    use DICTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self[]
     */
    protected static $instances = [];
    /**
     * @var ilObjUser
     */
    protected $user;


    /**
     * Repository constructor
     *
     * @param ilObjUser $user
     */
    private function __construct(ilObjUser $user)
    {
        $this->user = $user;
    }


    /**
     * @param ilObjUser $user
     *
     * @return self
     */
    public static function getInstance(ilObjUser $user) : self
    {
        if (!isset(self::$instances[$user->getId()])) {
            self::$instances[$user->getId()] = new self($user);
        }

        return self::$instances[$user->getId()];
    }


    /**
     * @param int $obj_ref_id
     */
    public function addToFavorites(int $obj_ref_id)/*: void*/
    {
        self::dic()->favourites()->add($this->user->getId(), $obj_ref_id);
    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {

    }


    /**
     * @return bool
     */
    public function enabled() : bool
    {
        return (boolval(self::dic()->settings()->get("disable_my_offers")) === false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @return array
     */
    public function getFavorites() : array
    {
        $favorites = self::dic()->favourites()->getFavouritesOfUser($this->user->getId());

        $children = array_map(function (array $favorite) : array {
            return [
                "child"       => $favorite["ref_id"],
                "type"        => $favorite["type"],
                "description" => $favorite["description"],
                "position"    => null,
                "path"        => null,
                "title"       => $favorite["title"],
                "parent_ref"  => $favorite["parent_ref"]
            ];
        }, $favorites);

        return $children;
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasFavorite(int $obj_ref_id) : bool
    {
        return boolval(self::dic()->favourites()->ifIsFavourite($this->user->getId(), $obj_ref_id));
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {

    }


    /**
     * @param int $obj_ref_id
     */
    public function removeFromFavorites(int $obj_ref_id)/*: void*/
    {
        self::dic()->favourites()->remove($this->user->getId(), $obj_ref_id);
    }
}
