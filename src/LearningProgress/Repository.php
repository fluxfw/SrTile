<?php

namespace srag\Plugins\SrTile\LearningProgress;

use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\LearningProgress
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
     * @internal
     */
    public function dropTables()/*:void*/
    {
        self::dic()->database()->dropTable(LearningProgressFilter::TABLE_NAME, false);
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
     * @return array
     */
    public function getFilter(int $obj_ref_id) : array
    {
        /**
         * @var LearningProgressFilter $learningProgressFilter
         */

        $learningProgressFilter = LearningProgressFilter::where([
            "obj_ref_id" => $obj_ref_id,
            "user_id"    => $this->user->getId()
        ])->first();

        if ($learningProgressFilter !== null) {
            return $learningProgressFilter->getFilter();
        }

        return [];
    }


    /**
     * @internal
     */
    public function installTables()/*:void*/
    {
        LearningProgressFilter::updateDB();
    }


    /**
     * @param int   $obj_ref_id
     * @param array $filter
     */
    public function setFilter(int $obj_ref_id, array $filter)/*: void*/
    {
        /**
         * @var LearningProgressFilter $learningProgressFilter
         */

        $learningProgressFilter = LearningProgressFilter::where([
            "obj_ref_id" => $obj_ref_id,
            "user_id"    => $this->user->getId()
        ])->first();

        if ($learningProgressFilter === null) {
            $learningProgressFilter = $this->factory()->newInstance();

            $learningProgressFilter->setObjRefId($obj_ref_id);

            $learningProgressFilter->setUserId($this->user->getId());
        }

        $learningProgressFilter->setFilter($filter);

        $this->storeLearningProgressFilter($learningProgressFilter);
    }


    /**
     * @param LearningProgressFilter $learningProgressFilter
     */
    protected function storeLearningProgressFilter(LearningProgressFilter $learningProgressFilter)/*:void*/
    {
        $learningProgressFilter->store();
    }
}
