<?php

namespace srag\Plugins\SrTile\Rating;

use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class RatingGUI
 *
 * @package           srag\Plugins\SrTile\Rating
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Rating\RatingGUI: ilUIPluginRouterGUI
 */
class RatingGUI
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CMD_LIKE = "like";
    const CMD_UNLIKE = "unlike";
    const LANG_MODULE_RATING = "rating";
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * RatingGUI constructor
     */
    public function __construct()
    {
        $this->tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        if (!($this->tile->getEnableRating() === Tile::SHOW_TRUE
            && self::access()->hasReadAccess($this->tile->getObjRefId()))
        ) {
            return;
        }

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
    protected function like()/*: void*/
    {
        $parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

        self::rating(self::dic()->user())->like($this->tile->getObjRefId());

        ilUtil::sendSuccess(self::plugin()->translate("liked", self::LANG_MODULE_RATING), true);

        if (!empty($parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
        }
    }


    /**
     *
     */
    protected function unlike()/*: void*/
    {
        $parent_ref_id = intval(filter_input(INPUT_GET, "parent_ref_id"));

        self::rating(self::dic()->user())->unlike($this->tile->getObjRefId());

        ilUtil::sendSuccess(self::plugin()->translate("unliked", self::LANG_MODULE_RATING), true);

        if (!empty($parent_ref_id)) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent_ref_id));
        } else {
            self::dic()->ctrl()->redirectByClass(ilPersonalDesktopGUI::class, "jumpToSelectedItems");
        }
    }
}
