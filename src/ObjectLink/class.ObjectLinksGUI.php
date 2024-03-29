<?php

namespace srag\Plugins\SrTile\ObjectLink;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilSrTilePlugin;
use ilUIPluginRouterGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ObjectLinksGUI
 *
 * @package           srag\Plugins\SrTile\ObjectLink
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\ObjectLink\ObjectLinksGUI: srag\Plugins\SrTile\Tile\TileGUI
 */
class ObjectLinksGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_LIST_OBJECT_LINKS = "listObjectLinks";
    const GET_PARAM_GROUP_ID = "group_id";
    const LANG_MODULE = "object_links";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const TAB_LIST_OBJECT_LINKS = "list_object_links";
    /**
     * @var Group
     */
    protected $group;
    /**
     * @var TileGUI
     */
    protected $parent;


    /**
     * ObjectLinksGUI constructor
     */
    public function __construct(TileGUI $parent)
    {
        $this->parent = $parent;
    }


    /**
     *
     */
    public static function addTabs() : void
    {
        if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS)) {
            self::dic()->tabs()->addTab(self::TAB_LIST_OBJECT_LINKS, self::plugin()->translate("object_links", self::LANG_MODULE), self::dic()->ctrl()->getLinkTargetByClass([
                ilUIPluginRouterGUI::class,
                TileGUI::class,
                self::class
            ], self::CMD_LIST_OBJECT_LINKS));
        }
    }


    /**
     *
     */
    public function executeCommand() : void
    {
        $this->group = self::srTile()->objectLinks()->getGroupByObject($this->parent->getTile()->getObjRefId());

        if (!self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS)) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_GROUP_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ObjectLinkGUI::class):
                self::dic()->ctrl()->forwardCommand(new ObjectLinkGUI($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_LIST_OBJECT_LINKS:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return Group
     */
    public function getGroup() : Group
    {
        return $this->group;
    }


    /**
     * @return TileGUI
     */
    public function getParent() : TileGUI
    {
        return $this->parent;
    }


    /**
     *
     */
    protected function listObjectLinks() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_LIST_OBJECT_LINKS);

        $table = self::srTile()->objectLinks()->factory()->newTableInstance($this);

        self::output()->output($table, true);
    }


    /**
     *
     */
    protected function setTabs() : void
    {

    }
}
