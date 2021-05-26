<?php

namespace srag\DataTableUI\SrTile\Component\Settings\Storage;

/**
 * Interface Factory
 *
 * @package srag\DataTableUI\SrTile\Component\Settings\Storage
 */
interface Factory
{

    /**
     * @return SettingsStorage
     */
    public function default() : SettingsStorage;
}
