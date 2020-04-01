<?php

namespace srag\Plugins\SrTile\Access;

use ilObject;
use ilObjUser;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Certificate\Certificates;
use srag\Plugins\SrTile\LearningProgress\LearningProgress;
use srag\Plugins\SrTile\LearningProgress\LearningProgressBar;
use srag\Plugins\SrTile\Metadata\Metadata;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Ilias
 *
 * @package srag\Plugins\SrTile\Access
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Ilias
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
     * Ilias constructor
     */
    private function __construct()
    {

    }


    /**
     * @param ilObjUser $user
     * @param Tile      $tile
     *
     * @return Certificates
     */
    public function certificates(ilObjUser $user, Tile $tile) : Certificates
    {
        return Certificates::getInstance($user, $tile);
    }


    /**
     * @return Courses
     */
    public function courses() : Courses
    {
        return Courses::getInstance();
    }


    /**
     * @param ilObjUser $user
     *
     * @return LearningProgress
     */
    public function learningProgress(ilObjUser $user) : LearningProgress
    {
        return LearningProgress::getInstance($user);
    }


    /**
     * @param ilObjUser $user
     *
     * @return LearningProgressBar
     */
    public function learningProgressBar(ilObjUser $user, int $obj_ref_id) : LearningProgressBar
    {
        return LearningProgressBar::getInstance($user, $obj_ref_id);
    }


    /**
     * @param ilObject $il_object
     *
     * @return Metadata
     */
    public function metadata(ilObject $il_object) : Metadata
    {
        return Metadata::getInstance($il_object);
    }
}
