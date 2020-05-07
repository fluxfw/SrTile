<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class AbstractCollection
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
abstract class AbstractCollection implements CollectionInterface
{

    use DICTrait;
    use SrTileTrait;

    /**
     * @var array
     */
    protected $obj_ref_ids = [];
    /**
     * @var tile[]
     */
    private $tiles = [];


    /**
     * AbstractCollection constructor
     */
    protected function __construct()
    {
        $this->read();
    }


    /**
     * @inheritDoc
     */
    public function addTile(Tile $tile)/*: void*/
    {
        $this->tiles[$tile->getTileId()] = $tile;
    }


    /**
     * @inheritDoc
     */
    public function removeTile(int $tile_id)/*: void*/
    {
        if (isset($this->tiles[$tile_id])) {
            unset($this->tiles[$tile_id]);
        }
    }


    /**
     * @inheritDoc
     */
    public function getTiles() : array
    {
        return $this->tiles;
    }


    /**
     *
     */
    protected function read() /*: void*/
    {
        $this->initObjRefIds();

        if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS)) {
            $this->obj_ref_ids = array_filter($this->obj_ref_ids, [self::srTile()->objectLinks(), "shouldShowObjectLink"]);
        }

        foreach ($this->obj_ref_ids as $obj_ref_id) {

            $tile = self::srTile()->tiles()->getInstanceForObjRefId($obj_ref_id);

            if (self::srTile()->access()->hasVisibleAccess($tile->getObjRefId())) {
                $this->addTile($tile);
            }
        }
    }


    /**
     *
     */
    protected abstract function initObjRefIds() /*: void*/
    ;
}
