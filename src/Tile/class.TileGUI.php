<?php

namespace srag\Plugins\SrTile\Tile;

require_once __DIR__ . "/../../vendor/autoload.php";

use ilLink;
use ilSrTilePlugin;
use ilUIPluginRouterGUI;
use ilUtil;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\ObjectLink\ObjectLinksGUI;
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

    const CMD_BACK_TO_OBJECT = "backToObject";
    const CMD_BACK_TO_PARENT = "backToParent";
    const CMD_EDIT_TILE = "editTile";
    const CMD_GET_PRECONDITIONS = "getPreconditions";
    const CMD_UPDATE_TILE = "updateTile";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "tile";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const TAB_TILE = "tile";
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * TileGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @param int $obj_ref_id
     */
    public static function addTabs(int $obj_ref_id)/*:void*/
    {
        self::dic()->ctrl()->setParameterByClass(self::class, self::GET_PARAM_REF_ID, $obj_ref_id);

        self::dic()->tabs()->addTab(self::TAB_TILE, ilSrTilePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTargetByClass([
            ilUIPluginRouterGUI::class,
            self::class
        ], self::CMD_EDIT_TILE));
    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->tile = self::srTile()->tiles()->getInstanceForObjRefId(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID)));

        if (!self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId())) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(ObjectLinksGUI::class):
                self::dic()->ctrl()->forwardCommand(new ObjectLinksGUI($this));
                break;

            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_BACK_TO_OBJECT:
                    case self::CMD_BACK_TO_PARENT:
                    case self::CMD_EDIT_TILE:
                    case self::CMD_GET_PRECONDITIONS:
                    case self::CMD_UPDATE_TILE:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @return Tile
     */
    public function getTile() : Tile
    {
        return $this->tile;
    }


    /**
     *
     */
    protected function backToObject()/*: void*/
    {
        self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($this->tile->getObjRefId()));
    }


    /**
     *
     */
    protected function backToParent()/*: void*/
    {
        $parent = self::srTile()->tiles()->getParentTile($this->tile);

        if (self::srTile()->tiles()->isObject($parent->getObjRefId())) {
            self::dic()->ctrl()->redirectToURL(ilLink::_getStaticLink($parent->getObjRefId()));
        }
    }


    /**
     *
     */
    protected function editTile()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_TILE);

        $form = self::srTile()->tiles()->factory()->newFormInstance($this, $this->tile);

        self::output()->output($form, true);
    }


    /**
     *
     */
    protected function getPreconditions()/*: void*/
    {
        $preconditions = self::srTile()->ilias()->courses()->getPreconditions($this->tile->getObjRefId());

        self::output()->output(self::srTile()->tiles()->renderer()->factory()->newCollectionGUIInstance()->fixed($preconditions));
    }


    /**
     *
     */
    protected function setTabs()/*: void*/
    {
        self::dic()->tabs()->clearTargets();

        $parent = self::srTile()->tiles()->getParentTile($this->tile);
        if (self::srTile()->tiles()->isObject($parent->getObjRefId())) {
            self::dic()->tabs()->setBack2Target($parent->_getTitle(), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_BACK_TO_PARENT));
        }

        self::dic()->tabs()->setBackTarget($this->tile->_getTitle(), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_BACK_TO_OBJECT));

        self::dic()->tabs()->addTab(self::TAB_TILE, self::plugin()->translate("edit_tile", self::LANG_MODULE), self::dic()->ctrl()->getLinkTargetByClass([
            ilUIPluginRouterGUI::class,
            self::class
        ], self::CMD_EDIT_TILE));

        ObjectLinksGUI::addTabs();
    }


    /**
     *
     */
    protected function updateTile()/*: void*/
    {
        self::dic()->tabs()->activateTab(self::TAB_TILE);

        $form = self::srTile()->tiles()->factory()->newFormInstance($this, $this->tile);

        if (!$form->storeForm()) {
            self::output()->output($form, true);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE), true);

        self::dic()->ctrl()->redirect($this, self::CMD_EDIT_TILE);
    }
}
