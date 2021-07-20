<?php

namespace srag\DataTableUI\SrTile\Component;

use srag\DataTableUI\SrTile\Component\Column\Column;
use srag\DataTableUI\SrTile\Component\Column\Factory as ColumnFactory;
use srag\DataTableUI\SrTile\Component\Data\Factory as DataFactory;
use srag\DataTableUI\SrTile\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\SrTile\Component\Format\Factory as FormatFactory;
use srag\DataTableUI\SrTile\Component\Settings\Factory as SettingsFactory;
use srag\DIC\SrTile\Plugin\PluginInterface;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrTile\Component
 */
interface Factory
{

    /**
     * @return ColumnFactory
     */
    public function column() : ColumnFactory;


    /**
     * @return DataFactory
     */
    public function data() : DataFactory;


    /**
     * @return FormatFactory
     */
    public function format() : FormatFactory;


    /**
     * @param PluginInterface $plugin
     */
    public function installLanguages(PluginInterface $plugin) : void;


    /**
     * @return SettingsFactory
     */
    public function settings() : SettingsFactory;


    /**
     * @param string      $table_id
     * @param string      $action_url
     * @param string      $title
     * @param Column[]    $columns
     * @param DataFetcher $data_fetcher
     *
     * @return Table
     */
    public function table(string $table_id, string $action_url, string $title, array $columns, DataFetcher $data_fetcher) : Table;
}
