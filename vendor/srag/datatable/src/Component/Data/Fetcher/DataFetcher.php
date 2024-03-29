<?php

namespace srag\DataTableUI\SrTile\Component\Data\Fetcher;

use srag\DataTableUI\SrTile\Component\Data\Data;
use srag\DataTableUI\SrTile\Component\Settings\Settings;
use srag\DataTableUI\SrTile\Component\Table;

/**
 * Interface DataFetcher
 *
 * @package srag\DataTableUI\SrTile\Component\Data\Fetcher
 */
interface DataFetcher
{

    /**
     * @param Settings $settings
     *
     * @return Data
     */
    public function fetchData(Settings $settings) : Data;


    /**
     * @param Table $component
     *
     * @return string
     */
    public function getNoDataText(Table $component) : string;


    /**
     * @return bool
     */
    public function isFetchDataNeedsFilterFirstSet() : bool;
}
