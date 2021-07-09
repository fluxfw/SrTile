<?php

namespace srag\Plugins\SrTile\Tile;

use ilContainerReference;
use ilObjectFactory;

/**
 * Class TileReference
 *
 * @package srag\Plugins\SrTile\Tile
 */
class TileReference extends Tile
{

    /**
     * @var int
     */
    protected $source_obj_ref_id;


    /**
     * @inheritDoc
     */
    public static function modifyTileRefIdForRead(int $obj_ref_id = null) : ?int
    {
        // Get and set tile config for target object
        return ilContainerReference::_lookupTargetRefId(self::dic()->objDataCache()->lookupObjId($obj_ref_id));
    }


    /**
     * @inheritDoc
     */
    public function _getIlObject() : ?ilObject
    {
        // But get title, type, ... from source object
        if ($this->il_object === null) {
            $this->il_object = ilObjectFactory::getInstanceByRefId($this->getSourceObjRefId(), false);

            if ($this->il_object === false) {
                $this->il_object = null;
            }
        }

        return $this->il_object;
    }


    /**
     * @return int
     */
    public function getSourceObjRefId() : int
    {
        return $this->source_obj_ref_id;
    }


    /**
     * @param int $source_obj_ref_id
     */
    public function setSourceObjRefId(int $source_obj_ref_id) : void
    {
        $this->source_obj_ref_id = $source_obj_ref_id;
    }
}
