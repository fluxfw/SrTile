<?php

namespace srag\Plugins\SrTile\OnlineStatus;

use ilObjectFactory;
use ilSrTilePlugin;
use ReflectionMethod;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Repository
 *
 * @package srag\Plugins\SrTile\OnlineStatus
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
     * @internal
     */
    public function dropTables()/*:void*/
    {

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

    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function isOnline(int $obj_ref_id) : bool
    {
        $obj_id = self::dic()->objDataCache()->lookupObjId($obj_ref_id);

        $type = self::dic()->objDataCache()->lookupType($obj_id);

        if (self::dic()->objDefinition()->isPluginTypeName($type)) {
            $class = "ilObj" . self::dic()->objDefinition()->getClassName(self::dic()->objDataCache()->lookupType($obj_id)) . "Access";

            if (method_exists($class, "checkOnline")) {
                return $class::checkOnline($obj_id);
            }

            return (!$class::_isOffline($obj_id));
        } else {
            $obj = ilObjectFactory::getInstanceByObjId($obj_id, false);

            return (!$obj->getOfflineStatus());
        }
    }


    /**
     * @param int  $obj_ref_id
     * @param bool $online
     */
    public function setOnline(int $obj_ref_id, bool $online)/*:void*/
    {
        $obj_id = self::dic()->objDataCache()->lookupObjId($obj_ref_id);

        $type = self::dic()->objDataCache()->lookupType($obj_id);

        if (!self::dic()->objDefinition()->isPluginTypeName($type)) {
            $obj = ilObjectFactory::getInstanceByObjId($obj_id, false);

            $obj->setOfflineStatus(!$online);

            $obj->update();
        }
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function supportsReadOnline(int $obj_ref_id) : bool
    {
        $obj_id = self::dic()->objDataCache()->lookupObjId($obj_ref_id);

        $type = self::dic()->objDataCache()->lookupType($obj_id);

        if (self::dic()->objDefinition()->isPluginTypeName($type)) {
            $class = "ilObj" . self::dic()->objDefinition()->getClassName(self::dic()->objDataCache()->lookupType($obj_id)) . "Access";

            if (!class_exists($class)) {
                return false;
            }

            if (method_exists($class, "checkOnline")) {
                return true;
            }

            if ((new ReflectionMethod($class, "_isOffline"))->getDeclaringClass()->getName() === $class) {
                return true;
            }

            return false;
        } else {
            return self::dic()->objDefinition()->supportsOfflineHandling($type);
        }
    }


    /**
     * @param int $obj_ref_id
     *
     * @return bool
     */
    public function supportsWriteOnline(int $obj_ref_id) : bool
    {
        $obj_id = self::dic()->objDataCache()->lookupObjId($obj_ref_id);

        $type = self::dic()->objDataCache()->lookupType($obj_id);

        if (self::dic()->objDefinition()->isPluginTypeName($type)) {
            return false;
        } else {
            return self::dic()->objDefinition()->supportsOfflineHandling($type);
        }
    }
}
