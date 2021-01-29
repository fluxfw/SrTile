<?php

namespace srag\Plugins\SrTile\OnlineStatus;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilDashboardGUI;
use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\ObjectLink\ObjectLink;
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

    const CMD_SET_OFFLINE = "setOffline";
    const CMD_SET_ONLINE = "setOnline";
    const GET_PARAM_PARENT_REF_ID = "parent_ref_id";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "onlinestatus";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var int[]
     */
    protected $object_ref_ids = [];
    /**
     * @var int
     */
    protected $parent_ref_id;


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

        if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS)) {
            $this->object_ref_ids = array_map(function (ObjectLink $object_link) : int {
                return $object_link->getObjRefId();
            }, self::srTile()->objectLinks()->getObjectLinks(self::srTile()->objectLinks()->getGroupByObject(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID)))->getGroupId()));
        } else {
            $this->object_ref_ids = [intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID))];
        }
        $this->object_ref_ids = array_filter($this->object_ref_ids, function (int $object_ref_id) : bool {
            return (self::srTile()->access()->hasWriteAccess($object_ref_id) && self::srTile()->onlineStatus()->supportsWriteOnline($object_ref_id));
        });

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
    protected function setOffline()/*: void*/
    {
        if (!empty($this->object_ref_ids)) {
            foreach ($this->object_ref_ids as $object_ref_id) {
                self::srTile()->onlineStatus()->setOnline($object_ref_id, false);

                ilSrTileUIHookGUI::askAndDisplayAlertMessage("setted_offline", self::LANG_MODULE);
            }
        }

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            if (self::version()->is6()) {
                self::dic()->ctrl()->redirectByClass(ilDashboardGUI::class, "jumpToSelectedItems");
            } else {
                self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
            }
        }
    }


    /**
     *
     */
    protected function setOnline()/*: void*/
    {
        if (!empty($this->object_ref_ids)) {
            foreach ($this->object_ref_ids as $object_ref_id) {
                self::srTile()->onlineStatus()->setOnline($object_ref_id, true);

                ilSrTileUIHookGUI::askAndDisplayAlertMessage("setted_online", self::LANG_MODULE);
            }
        }

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            if (self::version()->is6()) {
                self::dic()->ctrl()->redirectByClass(ilDashboardGUI::class, "jumpToSelectedItems");
            } else {
                self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
            }
        }
    }


    /**
     *
     */
    protected function setTabs()/*:void*/
    {

    }
}
