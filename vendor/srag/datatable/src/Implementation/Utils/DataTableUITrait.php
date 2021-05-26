<?php

namespace srag\DataTableUI\SrTile\Implementation\Utils;

use srag\DataTableUI\SrTile\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrTile\Implementation\Factory;

/**
 * Trait DataTableUITrait
 *
 * @package srag\DataTableUI\SrTile\Implementation\Utils
 */
trait DataTableUITrait
{

    /**
     * @return FactoryInterface
     */
    protected static function dataTableUI() : FactoryInterface
    {
        return Factory::getInstance();
    }
}
