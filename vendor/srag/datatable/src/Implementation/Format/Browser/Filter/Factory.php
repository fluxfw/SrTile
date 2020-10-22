<?php

namespace srag\DataTableUI\SrTile\Implementation\Format\Browser\Filter;

use srag\CustomInputGUIs\SrTile\FormBuilder\FormBuilder as FormBuilderInterface;
use srag\DataTableUI\SrTile\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrTile\Component\Format\Browser\Filter\Factory as FactoryInterface;
use srag\DataTableUI\SrTile\Component\Settings\Settings;
use srag\DataTableUI\SrTile\Component\Table;
use srag\DataTableUI\SrTile\Implementation\Utils\DataTableUITrait;
use srag\DIC\SrTile\DICTrait;

/**
 * Class Factory
 *
 * @package srag\DataTableUI\SrTile\Implementation\Format\Browser\Filter
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
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilderInterface
    {
        return new FormBuilder($parent, $component, $settings);
    }
}
