<?php

namespace srag\DataTableUI\SrTile\Component\Utils;

use srag\DataTableUI\SrTile\Component\Table;

/**
 * Interface TableBuilder
 *
 * @package srag\DataTableUI\SrTile\Component\Utils
 */
interface TableBuilder
{

    /**
     * @return Table
     */
    public function getTable() : Table;


    /**
     * @return string
     */
    public function render() : string;
}
