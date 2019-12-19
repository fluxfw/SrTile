<?php

namespace srag\Plugins\SrTile\Rating;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\Rating
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
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
     * @param Rating $rating
     */
    protected function deleteRating(Rating $rating)/*:void*/
    {
        $rating->delete();
    }


    /**
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(Rating::TABLE_NAME, false);
    }


    /**
     * @return Factory
     */
    public function factory() : Factory
    {
        return Factory::getInstance();
    }


    /**
     * @param int $obj_ref_id
     *
     * @return int
     */
    public function getLikesCount($obj_ref_id) : int
    {
        $obj_id = intval(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

        return Rating::where([
            "obj_id" => $obj_id
        ])->count();
    }


    /**
     * @param int $obj_id
     *
     * @return Rating|null
     */
    public function getRating(int $obj_id)/*: ?Rating*/
    {
        /**
         * @var Rating|null $rating
         */

        $rating = Rating::where([
            "obj_id"  => $obj_id,
            "user_id" => $this->user->getId()
        ])->first();

        return $rating;
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function hasLike(int $obj_ref_id) : bool
    {
        $obj_id = intval(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

        return ($this->getRating($obj_id) !== null);
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        Rating::updateDB();
    }


    /**
     * @param int $obj_ref_id
     */
    public function like(int $obj_ref_id)/*: void*/
    {
        $obj_id = intval(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

        $rating = $this->getRating($obj_id);

        if ($rating === null) {
            $rating = $this->factory()->newInstance();

            $rating->setObjId($obj_id);

            $rating->setUserId($this->user->getId());

            $this->storeRating($rating);
        }
    }


    /**
     * @param Rating $rating
     */
    protected function storeRating(Rating $rating)/*:void*/
    {
        $rating->store();
    }


    /**
     * @param int $obj_ref_id
     */
    public function unlike(int $obj_ref_id)/*: void*/
    {
        $obj_id = intval(self::dic()->objDataCache()->lookupObjId($obj_ref_id));

        $rating = $this->getRating($obj_id);

        if ($rating !== null) {
            $this->deleteRating($rating);
        }
    }
}
