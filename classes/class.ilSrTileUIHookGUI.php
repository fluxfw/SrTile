<?php

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\Config;
use srag\Plugins\SrTile\Recommend\RecommendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;
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
    const TAB_PERM_ID = "perm";
    const ADMIN_FOOTER_TPL_ID = "tpl.adm_content.html";
    const ACTIONS_MENU_TEMPLATE = "Services/UIComponent/AdvancedSelectionList/tpl.adv_selection_list.html";
    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    const GET_RENDER_EDIT_TILE_ACTION = "render_edit_tile_action";
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
     * @return int|null
     *
     * @deprecated
     */
    public static function filterRefId()/*: ?int*/
    {
        $obj_ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);

        if ($obj_ref_id === null) {
            $param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);

            $obj_ref_id = explode("_", $param_target)[1];
        }

        $obj_ref_id = intval($obj_ref_id);

        if ($obj_ref_id > 0) {
            return $obj_ref_id;
        } else {
            return null;
        }
    }


    /**
     * ilSrTileUIHookGUI constructor
     */
    public function __construct()
    {

    }


    /**
     * @inheritDoc
     */
    public function getHTML(/*string*/ $a_comp, /*string*/ $a_part, $a_par = []) : array
    {

        if ($this->matchRepository($a_part, $a_par)) {

            return [
                "mode" => self::REPLACE,
                "html" => self::output()->getHTML(self::srTile()->tiles()->renderer()->factory()->newCollectionGUIInstance()->container($a_par["html"]))
            ];
        }

        if ($this->matchFavorites($a_part, $a_par)) {

            return [
                "mode" => self::REPLACE,
                "html" => self::output()->getHTML(self::srTile()->tiles()->renderer()->factory()->newCollectionGUIInstance()->desktop(self::dic()->user()))
            ];
        }

        if ($this->matchRecommendModal($a_part, $a_par)) {

            return [
                "mode" => self::APPEND,
                "html" => (new RecommendGUI())->getModal()
            ];
        }

        if ($a_par["tpl_id"] === self::ACTIONS_MENU_TEMPLATE && $a_part === self::TEMPLATE_GET) {

            if (!empty(filter_input(INPUT_GET, self::GET_RENDER_EDIT_TILE_ACTION))) {

                $html = $a_par["html"];

                $matches = [];
                preg_match('/id="act_([0-9]+)/', $html, $matches);
                if (is_array($matches) && count($matches) >= 2) {

                    $obj_ref_id = intval($matches[1]);

                    if (self::srTile()->tiles()->isObject($obj_ref_id)) {

                        if (self::srTile()->access()->hasWriteAccess($obj_ref_id)) {

                            self::dic()->ctrl()->setParameterByClass(TileGUI::class, TileGUI::GET_PARAM_REF_ID, $obj_ref_id);

                            $edit_tile_html = '<li>' . self::output()->getHTML(self::dic()->ui()->factory()->link()->standard('<span class="xsmall">' . self::plugin()
                                        ->translate("edit_tile", TileGUI::LANG_MODULE) . '</span>',
                                    self::dic()->ctrl()->getLinkTargetByClass([
                                        ilUIPluginRouterGUI::class,
                                        TileGUI::class
                                    ], TileGUI::CMD_EDIT_TILE))) . '</li>';

                            $matches = [];
                            preg_match('/<ul class="dropdown-menu pull-right" role="menu" id="ilAdvSelListTable_.*">/',
                                $html, $matches);
                            if (is_array($matches) && count($matches) >= 1) {
                                $html = str_ireplace($matches[0], $matches[0] . $edit_tile_html, $html);
                            } else {
                                $html = $edit_tile_html . $html;
                            }

                            return ["mode" => self::REPLACE, "html" => $html];
                        }
                    }
                }
            }
        }

        return parent::getHTML($a_comp, $a_part, $a_par);
    }


    /**
     * @inheritDoc
     */
    public function modifyGUI(/*string*/ $a_comp, /*string*/ $a_part, /*array*/ $a_par = [])/*: void*/
    {
        $obj_ref_id = self::filterRefId();

        if ($this->matchToolbar($a_part)) {

            if (!self::srTile()->access()->hasWriteAccess($obj_ref_id)) {

                if (self::srTile()->tiles()->getInstanceForObjRefId($obj_ref_id)->getShowObjectTabs() === Tile::SHOW_FALSE) {
                    self::dic()->tabs()->clearTargets();
                    self::dic()->tabs()->clearSubTabs();
                }

                return;
            }

            if (count(array_filter(self::dic()->tabs()->target, function (array $tab) : bool {
                    return (strpos($tab["id"], self::TAB_PERM_ID) !== false);
                })) > 0
            ) {

                TileGUI::addTabs($obj_ref_id);

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
        $obj_ref_id = self::filterRefId();

        return (!self::$load[self::TOOLBAR_LOADER]
            && $baseClass !== strtolower(ilAdministrationGUI::class)
            && $a_part === self::PAR_TABS
            && (self::$load[self::TOOLBAR_LOADER] = true)
            && self::srTile()->tiles()->isObject($obj_ref_id));
    }


    /**
     * @param string $a_part
     * @param array  $a_par
     *
     * @return bool
     */
    protected function matchRepository(string $a_part, array $a_par) : bool
    {
        $obj_ref_id = self::filterRefId();

        return (!self::$load[self::REPOSITORY_LOADER]
            && $a_part === self::TEMPLATE_GET
            && $a_par["tpl_id"] === self::TEMPLATE_ID_REPOSITORY
            && (self::$load[self::REPOSITORY_LOADER] = true)
            && Config::getField(Config::KEY_ENABLED_ON_REPOSITORY)
            && !in_array(self::dic()->ctrl()->getCmd(), ["editOrder"])
            && !in_array(self::dic()->ctrl()->getCallHistory()[0]["cmd"], ["editOrder"])
            && !$_SESSION["il_cont_admin_panel"]
            && self::srTile()->tiles()->isObject($obj_ref_id)
            && self::srTile()->tiles()->getInstanceForObjRefId($obj_ref_id)->getView() !== Tile::VIEW_DISABLED);
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

        if (empty($should_not_display)) {
            ilUtil::{"send" . ucfirst($alert_type)}(self::plugin()->translate($key, $module), $keep);
        }
    }
}
