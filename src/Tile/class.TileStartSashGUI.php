<?php

namespace srag\Plugins\SrTile\Tile;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileStartSashGUI
 *
 * @package           srag\Plugins\SrTile\Tile
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Tile\TileStartSashGUI: ilUIPluginRouterGUI
 */
class TileStartSashGUI
{

    use DICTrait;
    use SrTileTrait;

    const CMD_START_SASH = "startSash";
    const GET_PARAM_REF_ID = "ref_id";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * TileStartSashGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->tile = self::srTile()->tiles()->getInstanceForObjRefId(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID)));

        if (!self::srTile()->access()->hasReadAccess($this->tile->getObjRefId())) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_START_SASH:
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
    protected function setTabs()/*: void*/
    {

    }


    /**
     *
     */
    protected function startSash()/*: void*/
    {
        $start_sahs = $this->tile->_getAdvancedLinkStartSahs($this->tile);

        if (isset($start_sahs["link"])) {
            self::dic()->ctrl()->redirectToURL($start_sahs["link"]);

            return;
        }

        self::dic()->ui()->mainTemplate()->addOnLoadCode($start_sahs["onclick"]);

        self::output()->output("",true);
    }
}
