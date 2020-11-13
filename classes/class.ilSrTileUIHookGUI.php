<?php

use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Recommend\RecommendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Tile\TileStartSahsGUI;
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

    const ACTIONS_MENU_TEMPLATE = "Services/UIComponent/AdvancedSelectionList/tpl.adv_selection_list.html";
    const ADMIN_FOOTER_TPL_ID = "tpl.adm_content.html";
    const DASHBOARD_LOADER = "tile_dashboard_loader";
    const GET_PARAM_REF_ID = "ref_id";
    const GET_PARAM_TARGET = "target";
    const GET_RENDER_EDIT_TILE_ACTION = "render_edit_tile_action";
    const PAR_TABS = "tabs";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const RECOMMEND_MODAL_LOADER = "tile_recommend_modal";
    const REPOSITORY_LOADER = "tile_repository_loader";
    const TAB_PERM_ID = "perm";
    const TEMPLATE_GET = "template_get";
    const TEMPLATE_ID_DASHBOARD = "src/UI/templates/default/Item/tpl.group.html";
    const TEMPLATE_ID_PERSONAL_DESKTOP = "Services/PersonalDesktop/tpl.pd_list_block.html";
    const TEMPLATE_ID_REPOSITORY = "Services/Container/tpl.container_list_block.html";
    const TOOLBAR_LOADER = "tile_toolbar_loader";
    /**
     * @var bool[]
     */
    protected static $load
        = [
            self::TOOLBAR_LOADER         => false,
            self::REPOSITORY_LOADER      => false,
            self::DASHBOARD_LOADER       => false,
            self::RECOMMEND_MODAL_LOADER => false
        ];


    /**
     * ilSrTileUIHookGUI constructor
     */
    public function __construct()
    {

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

        if ($this->matchDashboard($a_part, $a_par)) {

            return [
                "mode" => self::REPLACE,
                "html" => self::output()->getHTML(self::version()->is6() ? self::srTile()->tiles()->renderer()->factory()->newCollectionGUIInstance()->dashboard($a_par["html"])
                    : self::srTile()->tiles()->renderer()->factory()->newCollectionGUIInstance()->favorites(self::dic()->user()))
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
                if (!(is_array($matches) && count($matches) >= 2)) {
                    $matches = [];
                    preg_match('/[?&]ref_id=([0-9]+)/', $html, $matches);
                }
                if (is_array($matches) && count($matches) >= 2) {

                    $obj_ref_id = intval($matches[1]);

                    if (self::srTile()->tiles()->isObject($obj_ref_id)) {

                        if (self::srTile()->access()->hasWriteAccess($obj_ref_id)) {

                            self::dic()->ctrl()->setParameterByClass(TileGUI::class, TileGUI::GET_PARAM_REF_ID, $obj_ref_id);

                            $actions = [
                                [TileGUI::LANG_MODULE, "edit_tile", TileGUI::class, TileGUI::CMD_EDIT_TILE]
                            ];

                            $actions_html = self::output()->getHTML(array_map(function (array $action) : string {
                                return '<li>' . self::output()->getHTML(self::dic()->ui()->factory()->link()->standard('<span class="xsmall">' . self::plugin()
                                            ->translate($action[1], $action[0]) . '</span>',
                                        self::dic()->ctrl()->getLinkTargetByClass([
                                            ilUIPluginRouterGUI::class,
                                            $action[2]
                                        ], $action[3]))) . '</li>';
                            }, $actions));

                            $matches = [];
                            preg_match('/<ul\s+class="dropdown-menu pull-right"\s+role="menu"\s+id="ilAdvSelListTable_.*"\s*>/',
                                $html, $matches);
                            if (is_array($matches) && count($matches) >= 1) {
                                $html = str_ireplace($matches[0], $matches[0] . $actions_html, $html);
                            } else {
                                $html = $actions_html . $html;
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
    public function gotoHook()/*: void*/
    {
        $target = filter_input(INPUT_GET, "target");

        $matches = [];
        preg_match("/^uihk_" . ilSrTilePlugin::PLUGIN_ID . "_sahs(_(.*))?/uim", $target, $matches);

        if (is_array($matches) && count($matches) >= 1) {
            $tile = self::srTile()->tiles()->getInstanceForObjRefId(intval($matches[2]));

            if ($tile === null || !self::srTile()->access()->hasReadAccess($tile->getObjRefId())) {
                return;
            }

            self::dic()->ctrl()->setTargetScript("ilias.php"); // Fix ILIAS 5.3 bug
            self::dic()->ctrl()->initBaseClass(ilUIPluginRouterGUI::class); // Fix ILIAS bug

            self::dic()->ctrl()->setParameterByClass(TileStartSahsGUI::class, TileStartSahsGUI::GET_PARAM_REF_ID, $tile->getObjRefId());

            self::dic()->ctrl()->redirectByClass([ilUIPluginRouterGUI::class, TileStartSahsGUI::class], TileStartSahsGUI::CMD_START_SAHS);
        }
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
     * @param array  $a_par
     *
     * @return bool
     */
    protected function matchDashboard(string $a_part, array $a_par) : bool
    {
        $baseClass = strtolower(filter_input(INPUT_GET, "baseClass"));

        return (!self::$load[self::DASHBOARD_LOADER]
            && ($baseClass === strtolower(ilDashboardGUI::class) || $baseClass === strtolower(ilPersonalDesktopGUI::class))
            && $a_part === self::TEMPLATE_GET
            && ($a_par["tpl_id"] === self::TEMPLATE_ID_DASHBOARD || $a_par["tpl_id"] === self::TEMPLATE_ID_PERSONAL_DESKTOP)
            && (self::version()->is6() ? true : (self::$load[self::DASHBOARD_LOADER] = true))
            && self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_ON_DASHBOARD));
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
            && self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_ON_REPOSITORY)
            && !in_array(self::dic()->ctrl()->getCmd(), ["editOrder"])
            && !in_array(self::dic()->ctrl()->getCallHistory()[0]["cmd"], ["editOrder"])
            && !$_SESSION["il_cont_admin_panel"]
            && self::srTile()->tiles()->isObject($obj_ref_id)
            && self::srTile()->tiles()->getInstanceForObjRefId($obj_ref_id)->getView() !== Tile::VIEW_DISABLED);
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
}
