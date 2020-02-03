<?php

namespace srag\Plugins\SrTile\OnlineStatus;

use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class OnlineStatusGUI
 *
 * @package           srag\Plugins\SrTile\OnlineStatus
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\OnlineStatus\OnlineStatusGUI: ilUIPluginRouterGUI
 */
class OnlineStatusGUI
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CMD_SET_OFFLINE = "setOffline";
    const CMD_SET_ONLINE = "setOnline";
    const GET_PARAM_PARENT_REF_ID = "parent_ref_id";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "onlinestatus";
    /**
     * @var int
     */
    protected $parent_ref_id;
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * OnlineStatusGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->parent_ref_id = intval(filter_input(INPUT_GET, self::GET_PARAM_PARENT_REF_ID));
        $this->tile = self::srTile()->tiles()->getInstanceForObjRefId(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID)));

        if (!self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId()) || !self::srTile()->onlineStatus()->supportsWriteOnline($this->tile->getObjRefId())) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_PARENT_REF_ID);
        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch ($next_class) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_SET_OFFLINE:
                    case self::CMD_SET_ONLINE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*:void*/
    {

    }


    /**
     *
     */
    protected function setOffline()/*: void*/
    {
        self::srTile()->onlineStatus()->setOnline($this->tile->getObjRefId(), false);

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("setted_offline", self::LANG_MODULE);

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
        }
    }


    /**
     *
     */
    protected function setOnline()/*: void*/
    {
        self::srTile()->onlineStatus()->setOnline($this->tile->getObjRefId(), true);

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("setted_online", self::LANG_MODULE);

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
        }
    }
}
