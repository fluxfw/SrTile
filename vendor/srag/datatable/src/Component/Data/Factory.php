<?php

namespace srag\DataTableUI\SrTile\Component\Data;

use srag\DataTableUI\SrTile\Component\Data\Fetcher\Factory as FetcherFactory;
use srag\DataTableUI\SrTile\Component\Data\Row\Factory as RowFactory;
use srag\DataTableUI\SrTile\Component\Data\Row\RowData;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrTile\Component\Data
 */
interface Factory
{

    /**
     * @param RowData[] $data
     * @param int       $max_count
     *
     * @return Data
     */
    public function data(array $data, int $max_count) : Data;


    /**
     * @return FetcherFactory
     */
    public function fetcher() : FetcherFactory;


    /**
     * @return RowFactory
     */
    public function row() : RowFactory;
}
