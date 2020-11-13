<?php

namespace srag\DataTableUI\SrTile\Implementation;

use srag\DataTableUI\SrTile\Component\Column\Factory as ColumnFactoryInterface;
use srag\DataTableUI\SrTile\Component\Data\Factory as DataFactoryInterface;
use srag\DataTableUI\SrTile\Component\Data\Fetcher\DataFetcher;
use srag\DataTableUI\SrTile\Component\Factory as FactoryInterface;
use srag\DataTableUI\SrTile\Component\Format\Factory as FormatFactoryInterface;
use srag\DataTableUI\SrTile\Component\Settings\Factory as SettingsFactoryInterface;
use srag\DataTableUI\SrTile\Component\Table as TableInterface;
use srag\DataTableUI\SrTile\Implementation\Column\Factory as ColumnFactory;
use srag\DataTableUI\SrTile\Implementation\Data\Factory as DataFactory;
use srag\DataTableUI\SrTile\Implementation\Format\Factory as FormatFactory;
use srag\DataTableUI\SrTile\Implementation\Settings\Factory as SettingsFactory;
use srag\DataTableUI\SrTile\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrTile\DICTrait;
use srag\DIC\SrTile\Plugin\PluginInterface;
use srag\LibraryLanguageInstaller\SrTile\LibraryLanguageInstaller;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrTile\Implementation
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Factory implements FactoryInterface
{

    use DICTrait;
    use DataTableUITrait;

    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Factory constructor
     */
    private function __construct()
    {

    }


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
     * @inheritDoc
     */
    public function column() : ColumnFactoryInterface
    {
        return ColumnFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function data() : DataFactoryInterface
    {
        return DataFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function format() : FormatFactoryInterface
    {
        return FormatFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function installLanguages(PluginInterface $plugin)/* : void*/
    {
        LibraryLanguageInstaller::getInstance()->withPlugin($plugin)->withLibraryLanguageDirectory(__DIR__
            . "/../../lang")->updateLanguages();
    }


    /**
     * @inheritDoc
     */
    public function settings() : SettingsFactoryInterface
    {
        return SettingsFactory::getInstance();
    }


    /**
     * @inheritDoc
     */
    public function table(string $table_id, string $action_url, string $title, array $columns, DataFetcher $data_fetcher) : TableInterface
    {
        return new Table($table_id, $action_url, $title, $columns, $data_fetcher);
    }
}
