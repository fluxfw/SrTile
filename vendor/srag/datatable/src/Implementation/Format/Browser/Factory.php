<?php

namespace srag\DataTableUI\SrTile\Implementation\Format\Browser;

use srag\DataTableUI\SrTile\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrTile\Component\Format\Browser\Factory as FactoryInterface;
use srag\DataTableUI\SrTile\Component\Format\Browser\Filter\Factory as FilterFactoryInterface;
use srag\DataTableUI\SrTile\Implementation\Format\Browser\Filter\Factory as FilterFactory;
use srag\DataTableUI\SrTile\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrTile\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrTile\Implementation\Format\Browser
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
    public function default() : BrowserFormat
    {
        return new DefaultBrowserFormat();
    }


    /**
     * @inheritDoc
     */
    public function filter() : FilterFactoryInterface
    {
        return FilterFactory::getInstance();
    }
}
