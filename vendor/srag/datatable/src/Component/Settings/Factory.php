<?php

namespace srag\DataTableUI\SrTile\Component\Settings;

use ILIAS\UI\Component\ViewControl\Pagination;
use srag\DataTableUI\SrTile\Component\Settings\Sort\Factory as SortFactory;
use srag\DataTableUI\SrTile\Component\Settings\Storage\Factory as StorageFactory;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrTile\Component\Settings
 */
interface Factory
{

    /**
     * @param Pagination $pagination
     *
     * @return Settings
     */
    public function settings(Pagination $pagination) : Settings;


    /**
     * @return SortFactory
     */
    public function sort() : SortFactory;


    /**
     * @return StorageFactory
     */
    public function storage() : StorageFactory;
}
