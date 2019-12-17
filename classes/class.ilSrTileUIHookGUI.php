<?php

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\Recommend\RecommendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\TileListGUI\TileListContainerGUI\TileListContainerGUI;
use srag\Plugins\SrTile\TileListGUI\TileListDesktopGUI\TileListDesktopGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class ilSrTileUIHookGUI
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTileUIHookGUI extends ilUIHookPluginGUI
{

    use DICTrait;
    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const PAR_TABS = "tabs";
    const TEMPLATE_GET = "template_get";
    const TOOLBAR_LOADER = "tile_toolbar_loader";
    const REPOSITORY_LOADER = "tile_repository_loader";
    const FAVORITES_LOADER = "tile_desktop_loader";
    const RECOMMEND_MODAL_LOADER = "tile_recommend_modal";
    const TEMPLATE_ID_REPOSITORY = "Services/Container/tpl.container_list_block.html";
    const TEMPLATE_ID_FAVORITES = "Services/PersonalDesktop/tpl.pd_list_block.html";
    const TAB_ID = "tile";
    const TAB_PERM_ID = "perm";
    const ADMIN_FOOTER_TPL_ID = "tpl.adm_content.html";
    /**
     * @var bool[]
     */
    protected static $load
        = [
            self::TOOLBAR_LOADER         => false,
            self::REPOSITORY_LOADER      => false,
            self::FAVORITES_LOADER       => false,
            self::RECOMMEND_MODAL_LOADER => false
        ];


    /**
     * ilSrTileUIHookGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @param string $a_comp
     * @param string $a_part
     * @param array  $a_par
     *
     * @return array
     */
    public function getHTML(/*string*/
        $a_comp, /*string*/
        $a_part,
        $a_par = []
    ) : array {

        if ($this->matchRepository($a_part, $a_par)) {

            return [
                "mode" => self::REPLACE,
                "html" => self::output()->getHTML(new TileListContainerGUI($a_par["html"]))
            ];
        }

        if ($this->matchFavorites($a_part, $a_par)) {

            return [
                "mode" => self::REPLACE,
                "html" => self::output()->getHTML(new TileListDesktopGUI(self::dic()->user()))
            ];
        }

        if ($this->matchRecommendModal($a_part, $a_par)) {

            return [
                "mode" => self::APPEND,
                "html" => (new RecommendGUI())->getModal()
            ];
        }

        return parent::getHTML($a_comp, $a_part, $a_par);
    }


    /**
     * @param string $a_comp
     * @param string $a_part
     * @param array  $a_par
     */
    public function modifyGUI(/*string*/
        $a_comp, /*string*/
        $a_part, /*array*/
        $a_par = []
    )/*: void*/
    {
        $obj_ref_id = self::tiles()->filterRefId();

        if ($this->matchToolbar($a_part)) {

            if (!self::access()->hasWriteAccess($obj_ref_id)) {

                if (self::tiles()->getInstanceForObjRefId($obj_ref_id)->getShowObjectTabs() === Tile::SHOW_FALSE) {
                    self::dic()->tabs()->clearTargets();
                    self::dic()->tabs()->clearSubTabs();
                }

                return;
            }

            if (count(array_filter(self::dic()->tabs()->target, function (array $tab) : bool {
                    return (strpos($tab["id"], self::TAB_PERM_ID) !== false);
                })) > 0
            ) {

                self::dic()->ctrl()->setParameterByClass(TileGUI::class, TileGUI::GET_PARAM_OBJ_REF_ID, $obj_ref_id);

                self::dic()->tabs()->addTab(self::TAB_ID, ilSrTilePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    TileGUI::class
                ], TileGUI::CMD_EDIT_TILE));

                self::dic()->tabs()->target[count(self::dic()->tabs()->target) - 1]["cmd"] = [];
            }
        }
    }


    /**
     * @param string $a_part
     *
     * @return bool
     */
    protected function matchToolbar(string $a_part) : bool
    {
        $baseClass = strtolower(filter_input(INPUT_GET, "baseClass"));
        $obj_ref_id = self::tiles()->filterRefId();

        return (!self::$load[self::TOOLBAR_LOADER]
            && $baseClass !== strtolower(ilAdministrationGUI::class)
            && $a_part === self::PAR_TABS
            && (self::$load[self::TOOLBAR_LOADER] = true)
            && self::tiles()->isObject($obj_ref_id));
    }


    /**
     * @param string $a_part
     * @param array  $a_par
     *
     * @return bool
     */
    protected function matchRepository(string $a_part, array $a_par) : bool
    {
        $obj_ref_id = self::tiles()->filterRefId();

        return (!self::$load[self::REPOSITORY_LOADER]
            && $a_part === self::TEMPLATE_GET
            && $a_par["tpl_id"] === self::TEMPLATE_ID_REPOSITORY
            && (self::$load[self::REPOSITORY_LOADER] = true)
            && Config::getField(Config::KEY_ENABLED_ON_REPOSITORY)
            && !in_array(self::dic()->ctrl()->getCmd(), ["editOrder"])
            && !in_array(self::dic()->ctrl()->getCallHistory()[0]["cmd"], ["editOrder"])
            && !$_SESSION["il_cont_admin_panel"]
            && self::tiles()->isObject($obj_ref_id)
            && self::tiles()->getInstanceForObjRefId($obj_ref_id)->getView() !== Tile::VIEW_DISABLED);
    }


    /**
     * @param string $a_part
     * @param array  $a_par
     *
     * @return bool
     */
    protected function matchFavorites(string $a_part, array $a_par) : bool
    {
        $baseClass = strtolower(filter_input(INPUT_GET, "baseClass"));

        return (!self::$load[self::FAVORITES_LOADER]
            && $baseClass === strtolower(ilPersonalDesktopGUI::class)
            && $a_part === self::TEMPLATE_GET
            && $a_par["tpl_id"] === self::TEMPLATE_ID_FAVORITES
            && (self::$load[self::FAVORITES_LOADER] = true)
            && Config::getField(Config::KEY_ENABLED_ON_FAVORITES));
    }


    /**
     * @param string $a_part
     * @param array  $a_par
     *
     * @return bool
     */
    protected function matchRecommendModal(string $a_part, array $a_par) : bool
    {
        return (!self::$load[self::RECOMMEND_MODAL_LOADER]
            && $a_par["tpl_id"] === self::ADMIN_FOOTER_TPL_ID
            && (self::$load[self::RECOMMEND_MODAL_LOADER] = true));
    }


    /**
     * @param string $key
     * @param string $module
     * @param string $alert_type
     * @param bool   $keep
     */
    public static function askAndDisplayAlertMessage(string $key, string $module, string $alert_type = "success", bool $keep = true)/*: void*/
    {
        $should_not_display = [];

        self::dic()->appEventHandler()->raise(IL_COMP_PLUGIN . "/" . ilSrTilePlugin::PLUGIN_NAME, ilSrTilePlugin::EVENT_SHOULD_NOT_DISPLAY_ALERT_MESSAGE, [
            "lang_module"        => $module,
            "lang_key"           => $key,
            "alert_type"         => $alert_type,
            "should_not_display" => &$should_not_display // Unfortunately ILIAS Raise Event System not supports return results so use a referenced variable
        ]);

        if (count((array) $should_not_display) === 0) {
            ilUtil::{"send" . ucfirst($alert_type)}(self::plugin()->translate($key, $module), $keep);
        }
    }
}
