<?php

namespace srag\Plugins\SrTile\ObjectLink;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Factory
 *
 * @package srag\Plugins\SrTile\ObjectLink
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Factory
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


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
     * Factory constructor
     */
    private function __construct()
    {

    }


    /**
     * @return Group
     */
    public function newGroupInstance() : Group
    {
        $group = new Group();

        return $group;
    }


    /**
     * @return ObjectLink
     */
    public function newObjectLinkInstance() : ObjectLink
    {
        $object_link = new ObjectLink();

        return $object_link;
    }


    /**
     * @param ObjectLinksGUI $parent
     * @param string         $parent_cmd
     *
     * @return ObjectLinksTableGUI
     */
    public function newTableInstance(ObjectLinksGUI $parent, string $parent_cmd = ObjectLinksGUI::CMD_LIST_OBJECT_LINKS) : ObjectLinksTableGUI
    {
        $table = new ObjectLinksTableGUI($parent, $parent_cmd);

        return $table;
    }


    /**
     * @param ObjectLinkGUI $parent
     * @param ObjectLink    $object_link
     *
     * @return ObjectLinkFormGUI
     */
    public function newFormInstance(ObjectLinkGUI $parent, ObjectLink $object_link) : ObjectLinkFormGUI
    {
        $form = new ObjectLinkFormGUI($parent, $object_link);

        return $form;
    }
}
