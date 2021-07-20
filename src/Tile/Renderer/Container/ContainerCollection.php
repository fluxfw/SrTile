<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Container;

use srag\Plugins\SrTile\Tile\Renderer\AbstractCollection;

/**
 * Class ContainerCollection
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Container
 */
class ContainerCollection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $html;


    /**
     * ContainerCollection constructor
     *
     * @param string $html
     */
    public function __construct(string $html)
    {
        $this->html = $html;

        parent::__construct();
    }


    /**
     * @inheritDoc
     */
    protected function initObjRefIds() : void
    {
        $obj_ref_ids = [];

        preg_match_all('/\\s+(data-list-item-)?id\\s*=\\s*["\']{1}lg_div_([0-9]+)/', $this->html, $obj_ref_ids);

        if (is_array($obj_ref_ids) && count($obj_ref_ids) > 2 && is_array($obj_ref_ids[2]) && count($obj_ref_ids[2]) > 0) {

            $this->obj_ref_ids = array_map("intval", $obj_ref_ids[2]);
        }
    }
}
