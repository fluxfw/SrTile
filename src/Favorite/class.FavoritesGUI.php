<?php

namespace srag\Plugins\SrTile\Favorite;

use ilDashboardGUI;
use ilLink;
use ilPersonalDesktopGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class FavoritesGUI
 *
 * @package           srag\Plugins\SrTile\Favorite
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Favorite\FavoritesGUI: ilUIPluginRouterGUI
 */
class FavoritesGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_ADD_TO_FAVORITES = "addToFavorites";
    const CMD_REMOVE_FROM_FAVORITES = "removeFromFavorites";
    const GET_PARAM_PARENT_REF_ID = "parent_ref_id";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "favorites";
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
     * FavoritesGUI constructor
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

        if (!(self::srTile()->favorites(self::dic()->user())->enabled() && $this->tile->getShowFavoritesIcon() === Tile::SHOW_TRUE)) {
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
                    case self::CMD_ADD_TO_FAVORITES:
                    case self::CMD_REMOVE_FROM_FAVORITES:
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
    protected function addToFavorites()/*: void*/
    {
        self::srTile()->favorites(self::dic()->user())->addToFavorites($this->tile->getObjRefId());

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("added_to_favorites", self::LANG_MODULE);

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
    protected function removeFromFavorites()/*: void*/
    {
        self::srTile()->favorites(self::dic()->user())->removeFromFavorites($this->tile->getObjRefId());

        ilSrTileUIHookGUI::askAndDisplayAlertMessage("removed_from_favorites", self::LANG_MODULE);

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
