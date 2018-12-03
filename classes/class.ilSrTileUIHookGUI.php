<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see https://github.com/ILIAS-eLearning/ILIAS/tree/trunk/docs/LICENSE */

require_once __DIR__ . "/../vendor/autoload.php";

use srag\Plugins\SrTile\Utils\SrTileTrait;
use srag\DIC\SrTile\DICTrait;
use \srag\Plugins\SrTile\TileList\TileListContainerGUI;
use \srag\Plugins\SrTile\TileList\TileListDesktopGUI;
use srag\Plugins\srTile\Tile\TileList;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;

/**
 * Class ilSrTileUIHookGUI
 *
 * Generated by srag\PluginGenerator v0.9.2
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilSrTileUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const PAR_TABS = "tabs";
	const MAIN_TEMPLATE_ID = "tpl.main.html";
	const MAIN_MENU_TEMPLATE_ID = "Services/MainMenu/tpl.main_menu.html";
	const STARTUP_SCREEN_TEMPLATE_ID = "Services/Init/tpl.startup_screen.html";
	const TEMPLATE_ADD = "template_add";
	const TEMPLATE_GET = "template_get";
	const TEMPLATE_SHOW = "template_show";
	const TILE_CONFIG_TAB_LOADER = "tile_config_tab";
	const TILE_CONTAINER_LOADER = "tile_container";
	const TILE_DESKTOP_LOADER = "tile_desktop_loader";
	const SESSION_PROJECT_KEY = ilSrTilePlugin::PLUGIN_ID . "_project_key";
	const GET_PARAM_REF_ID = "ref_id";
	const GET_PARAM_TARGET = "target";
	const TEMPLATE_ID_CONTAINER_PAGE = "Services/Container/tpl.container_page.html";
	const TEMPLATE_ID_CONTAINER_LIST_ITEM = "Services/Container/tpl.container_list_item.html";
	const CMD_CLASS_PERSONALDESKTOP_GUI = "ilPersonalDesktopGUI";
	const TEMPLATE_ID_PERSONAL_DESKTOP = "Services/PersonalDesktop/tpl.pd_list_block.html";



	const GET = 'template_get';
	/**
	 * @var bool[]
	 */
	protected static $load = [
		self::TILE_CONFIG_TAB_LOADER => false,
		self::TILE_CONTAINER_LOADER => false,
		self::TILE_DESKTOP_LOADER => false
	];


	/**
	 * ilSrTileUIHookGUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @param string $a_comp
	 * @param string $a_part
	 * @param array  $a_par
	 *
	 * @return array
	 */
	public function modifyGUI(/*string*/

		$a_comp, /*string*/
		$a_part, /*array*/
		$a_par = []): array {


		if (!self::$load[self::TILE_CONFIG_TAB_LOADER]) {
			if ($a_part == self::PAR_TABS AND $_GET['ref_id']) {
				self::$load[self::TILE_CONFIG_TAB_LOADER] = true;
				self::dic()->ctrl()->saveParameterByClass(SrTileGUI::class, SrTileGUI::GET_PARAM_OBJ_REF_ID);
				$ilTabsGUI = $a_par['tabs'];
				$ilTabsGUI->addTab('tile', self::plugin()->translate('tile'), self::dic()->ctrl()->getLinkTargetByClass(array(
					'ilUIPluginRouterGUI',
					SrTileGUI::class,
				), SrTileGUI::CMD_EDIT_TILE));

				if (self::dic()->ctrl()->getCmdClass() == NULL) {
					$ilTabsGUI->setTabActive('view_content');
				}
			}
		}

		return [ "mode" => self::KEEP, "html" => "" ];
	}


	public function getHTML($a_comp, $a_part, $a_par = array()) {

		if (in_array(2, self::dic()->rbacreview()->assignedGlobalRoles(self::dic()->user()->getId()))) {
			//return array( "mode" => ilUIHookPluginGUI::KEEP, "html" => "" );
		}

		self::dic()->log()->write($a_par['tpl_id']);

		if ($a_part == self::GET
			&& ($a_par['tpl_id'] == self::TEMPLATE_ID_CONTAINER_PAGE
				|| $a_par['tpl_id'] == self::TEMPLATE_ID_CONTAINER_LIST_ITEM)) {
			if (self::dic()->ctrl() instanceof ilCtrl) {

				$ref_id = $this->filterRefId();

				//$main_tpl = self::dic()->mainTemplate();

				//Repository
				if ($ref_id > 0 && self::$load[self::TILE_CONTAINER_LOADER] == false) {
					self::$load[self::TILE_CONTAINER_LOADER] = true;

					$tile_list_gui = new TileListContainerGUI($ref_id);

					return [
						"mode" => ilUIHookPluginGUI::PREPEND,
						"html" => '</div>' . $tile_list_gui->render() . '<div class="ilCLI ilObjListRow row">'
					];
				}
			}
		}

		//Personal Desktop
		if (strtolower($_GET['baseClass']) == strtolower(self::CMD_CLASS_PERSONALDESKTOP_GUI)
			&& $a_par['tpl_id'] == self::TEMPLATE_ID_PERSONAL_DESKTOP
			&& self::$load[self::TILE_DESKTOP_LOADER] == false) {

			/*
			if(self::dic()->user()->getPref('pd_order_items') != ilPDSelectedItemsBlockViewSettings::SORT_BY_TYPE) {
				self::dic()->user()->writePref('pd_order_items', );
			}
			*/

			self::$load[self::TILE_DESKTOP_LOADER] = true;
			$tile_list_gui = new TileListDesktopGUI(self::dic()->user()->getId());


			return [
				"mode" => ilUIHookPluginGUI::PREPEND,
				"html" => $tile_list_gui->render()
			];
		}

		return parent::getHTML($a_comp, $a_part, $a_par); // TODO: Change the autogenerated stub

	}


	/**
	 * @return mixed
	 */
	public static function filterRefId() {
		$ref_id = filter_input(INPUT_GET, self::GET_PARAM_REF_ID);
		if (is_null($ref_id)) {
			$param_target = filter_input(INPUT_GET, self::GET_PARAM_TARGET);
			$ref_id = explode('_', $param_target)[1];
		}

		return $ref_id;
	}
}
