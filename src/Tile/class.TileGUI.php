<?php

namespace srag\Plugins\SrTile\Tile;

use ilLink;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use ilUIPluginRouterGUI;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\TileListGUI\TileListStaticGUI\TileListStaticGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileGUI
 *
 * @package           srag\Plugins\SrTile\Tile
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Tile\TileGUI: ilUIPluginRouterGUI
 */
class TileGUI
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CMD_EDIT_TILE = "editTile";
    const CMD_UPDATE_TILE = "updateTile";
    const CMD_CANCEL = "cancel";
    const GET_PRECONDITIONS = "getPreconditions";
    const LANG_MODULE_TILE = "tile";
    const GET_PARAM_OBJ_REF_ID = 'ref_id';


    /**
     * TileGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $this->setTabs();

                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_EDIT_TILE:
                    case self::CMD_UPDATE_TILE:
                    case self::CMD_CANCEL:
                    case self::GET_PRECONDITIONS;
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @param Tile $tile
     *
     * @return TileFormGUI
     */
    protected function getTileFormGUI(Tile $tile) : TileFormGUI
    {
        $form = new TileFormGUI($this, $tile);

        return $form;
    }


    /**
     *
     */
    protected function cancel()/*: void*/
    {
        $this->dic()->ctrl()->redirectToURL(ilLink::_getStaticLink(self::tiles()->filterRefId()));
    }


    /**
     *
     */
    protected function editTile()/*: void*/
    {
        $tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());

        self::dic()->ctrl()->setParameterByClass(self::class, self::GET_PARAM_OBJ_REF_ID, $tile->getObjRefId());

        $form = $this->getTileFormGUI($tile);

        self::output()->output($form, true);
    }


    /**
     *
     */
    protected function updateTile()/*: void*/
    {
        $tile = self::tiles()->getInstanceForObjRefId(self::tiles()->filterRefId());

        self::dic()->ctrl()->setParameterByClass(self::class, self::GET_PARAM_OBJ_REF_ID, $tile->getObjRefId());

        $form = $this->getTileFormGUI($tile);

        if (!$form->storeForm()) {
            self::output()->output($form, true);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE_TILE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_TILE);
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->clearTargets();

        self::dic()->ctrl()->setParameter($this, Tiles::GET_PARAM_REF_ID, self::tiles()->filterRefId());

        self::dic()->tabs()->addTab(ilSrTileUIHookGUI::TAB_ID, ilSrTilePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTargetByClass([
            ilUIPluginRouterGUI::class,
            self::class
        ], self::CMD_EDIT_TILE));

        self::dic()->tabs()->setBackTarget(self::plugin()->translate("back", self::LANG_MODULE_TILE), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_CANCEL));
    }


    /**
     *
     */
    protected function getPreconditions()/*: void*/
    {
        $obj_ref_id = self::tiles()->filterRefId();

        $preconditions = self::ilias()->courses()->getPreconditions($obj_ref_id);

        self::output()->output(new TileListStaticGUI($preconditions));
    }
}
