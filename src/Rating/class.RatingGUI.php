<?php

namespace srag\Plugins\SrTile\Rating;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilDashboardGUI;
use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class RatingGUI
 *
 * @package           srag\Plugins\SrTile\Rating
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Rating\RatingGUI: ilUIPluginRouterGUI
 */
class RatingGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_LIKE = "like";
    const CMD_UNLIKE = "unlike";
    const GET_PARAM_PARENT_REF_ID = "parent_ref_id";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "rating";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var int
     */
    protected $parent_ref_id;
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * RatingGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand() : void
    {
        $this->parent_ref_id = intval(filter_input(INPUT_GET, self::GET_PARAM_PARENT_REF_ID));
        $this->tile = self::srTile()->tiles()->getInstanceForObjRefId(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID)));

        if (!($this->tile->getEnableRating() === Tile::SHOW_TRUE
            && self::srTile()->access()->hasReadAccess($this->tile->getObjRefId()))
        ) {
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
                    case self::CMD_LIKE:
                    case self::CMD_UNLIKE:
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
    protected function like() : void
    {
        self::srTile()->ratings(self::dic()->user())->like($this->tile->getObjRefId());

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("liked", self::LANG_MODULE);

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilDashboardGUI::class, "jumpToSelectedItems");
        }
    }


    /**
     *
     */
    protected function setTabs() : void
    {

    }


    /**
     *
     */
    protected function unlike() : void
    {
        self::srTile()->ratings(self::dic()->user())->unlike($this->tile->getObjRefId());

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("unliked", self::LANG_MODULE);

        if (!empty($this->parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilDashboardGUI::class, "jumpToSelectedItems");
        }
    }
}
