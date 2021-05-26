<?php

namespace srag\DataTableUI\SrTile\Component\Format\Browser\Filter;

use srag\CustomInputGUIs\SrTile\FormBuilder\FormBuilder;
use srag\DataTableUI\SrTile\Component\Format\Browser\BrowserFormat;
use srag\DataTableUI\SrTile\Component\Settings\Settings;
use srag\DataTableUI\SrTile\Component\Table;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrTile\Component\Format\Browser\Filter
 */
interface Factory
{

    /**
     * @param BrowserFormat $parent
     * @param Table         $component
     * @param Settings      $settings
     *
     * @return FormBuilder
     */
    public function formBuilder(BrowserFormat $parent, Table $component, Settings $settings) : FormBuilder;
}
