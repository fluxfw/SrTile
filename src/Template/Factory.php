<?php

namespace srag\Plugins\SrTile\Template;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrTile\Template
 */
final class Factory
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
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
     * @param TemplateConfigGUI $parent
     * @param Template          $template
     *
     * @return TemplateFormGUI
     */
    public function newFormInstance(TemplateConfigGUI $parent, Template $template) : TemplateFormGUI
    {
        $form = new TemplateFormGUI($parent, $template);

        return $form;
    }


    /**
     * @return Template
     */
    public function newInstance() : Template
    {
        $template = new Template();

        return $template;
    }


    /**
     * @param TemplatesConfigGUI $parent
     * @param string             $cmd
     *
     * @return TemplatesTableGUI
     */
    public function newTableInstance(TemplatesConfigGUI $parent, string $cmd = TemplatesConfigGUI::CMD_LIST_TEMPLATES) : TemplatesTableGUI
    {
        $table = new TemplatesTableGUI($parent, $cmd);

        return $table;
    }
}
