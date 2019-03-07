<?php

require_once __DIR__ . "/../vendor/autoload.php";

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
class ilSrTileUIHookGUI extends ilUIHookPluginGUI {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const PAR_TABS = "tabs";
	const TEMPLATE_GET = "template_get";
	const TILE_CONFIG_TAB_LOADER = "tile_config_tab";
	const TILE_CONTAINER_LOADER = "tile_container";
	const TILE_FAVORITES_LOADER = "tile_desktop_loader";
	const TILE_RECOMMEND_MODAL = "tile_recommend_modal";
	const TEMPLATE_ID_REPOSITORY = "Services/Container/tpl.container_list_block.html";
	const TEMPLATE_ID_FAVORITES = "Services/PersonalDesktop/tpl.pd_list_block.html";
	const TAB_ID = "tile";
	const TAB_PERM_ID = "perm";
	const ADMIN_FOOTER_TPL_ID = "tpl.adm_content.html";
	/**
	 * @var bool[]
	 */
	protected static $load = [
		self::TILE_CONFIG_TAB_LOADER => false,
		self::TILE_CONTAINER_LOADER => false,
		self::TILE_FAVORITES_LOADER => false,
		self::TILE_RECOMMEND_MODAL => false
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
	public function getHTML(/*string*/
		$a_comp, /*string*/
		$a_part, $a_par = []): array {

		//Repository
		if ($this->loadTileContainerPossible($a_part, $a_par)) {

			self::$load[self::TILE_CONTAINER_LOADER] = true;

			$obj_ref_id = self::tiles()->filterRefId();

			if (self::tiles()->isObject($obj_ref_id) && self::tiles()->getInstanceForObjRefId($obj_ref_id)->getView() !== Tile::VIEW_DISABLED) {

				return [
					"mode" => ilUIHookPluginGUI::REPLACE,
					"html" => self::output()->getHTML(new TileListContainerGUI($a_par["html"]))
				];
			}
		}

		//Favorites
		if ($this->loadTileFavoritesPossible($a_part, $a_par)) {

			self::$load[self::TILE_FAVORITES_LOADER] = true;

			return [
				"mode" => ilUIHookPluginGUI::REPLACE,
				"html" => self::output()->getHTML(new TileListDesktopGUI(self::dic()->user()))
			];
		}

		// Recommend modal
		if (!self::$load[self::TILE_RECOMMEND_MODAL]) {
			if ($a_par["tpl_id"] === self::ADMIN_FOOTER_TPL_ID) {
				self::$load[self::TILE_RECOMMEND_MODAL] = true;

				return [
					"mode" => ilUIHookPluginGUI::APPEND,
					"html" => (new RecommendGUI())->getModal()
				];
			}
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
		$a_par = [])/*: void*/ {
		if (!self::$load[self::TILE_CONFIG_TAB_LOADER]) {

			$obj_ref_id = self::tiles()->filterRefId();

			if ($this->matchObjectBaseClass()
				&& $a_part === self::PAR_TABS
				&& self::tiles()->isObject($obj_ref_id)) {

				self::$load[self::TILE_CONFIG_TAB_LOADER] = true;

				if (!self::access()->hasWriteAccess($obj_ref_id)) {

					if (self::tiles()->getInstanceForObjRefId($obj_ref_id)->getShowObjectTabs() === Tile::SHOW_FALSE) {
						self::dic()->tabs()->clearTargets();
						self::dic()->tabs()->clearSubTabs();
					}

					return;
				}

				if (count(array_filter(self::dic()->tabs()->target, function (array $tab): bool {
						return (strpos($tab["id"], self::TAB_PERM_ID) !== - 1);
					})) > 0) {

					self::dic()->ctrl()->setParameterByClass(TileGUI::class, TileGUI::GET_PARAM_OBJ_REF_ID, $obj_ref_id);

					self::dic()->tabs()->addTab(self::TAB_ID, ilSrTilePlugin::PLUGIN_NAME, self::dic()->ctrl()->getLinkTargetByClass([
						ilUIPluginRouterGUI::class,
						TileGUI::class
					], TileGUI::CMD_EDIT_TILE));

					self::dic()->tabs()->target[count(self::dic()->tabs()->target) - 1]["cmd"] = [];
				}
			}
		}
	}


	/**
	 * @return bool
	 */
	protected function matchObjectBaseClass(): bool {
		$baseClass = strtolower(filter_input(INPUT_GET, "baseClass"));

		return ($baseClass === strtolower(ilRepositoryGUI::class) || $baseClass === strtolower(ilObjPluginDispatchGUI::class)
			|| $baseClass === strtolower(ilSAHSEditGUI::class)
			|| $baseClass === strtolower(ilLMEditorGUI::class)
			|| empty($baseClass));
	}


	/**
	 * @param string $a_part
	 * @param array  $a_par
	 *
	 * @return bool
	 */
	protected function loadTileContainerPossible(string $a_part, array $a_par): bool {
		return (!self::$load[self::TILE_CONTAINER_LOADER]
			&& Config::getField(Config::KEY_ENABLED_ON_REPOSITORY)
			&& $this->matchObjectBaseClass()
			&& $a_part === self::TEMPLATE_GET
			&& ($a_par["tpl_id"] === self::TEMPLATE_ID_REPOSITORY)
			&& !in_array(self::dic()->ctrl()->getCmd(), [ "editOrder" ])
			&& !in_array(self::dic()->ctrl()->getCallHistory()[0]["cmd"], [ "editOrder" ])
			&& !$_SESSION["il_cont_admin_panel"]);
	}


	/**
	 * @param string $a_part
	 * @param array  $a_par
	 *
	 * @return bool
	 */
	protected function loadTileFavoritesPossible(string $a_part, array $a_par): bool {
		$baseClass = strtolower(filter_input(INPUT_GET, "baseClass"));

		return (!self::$load[self::TILE_FAVORITES_LOADER]
			&& Config::getField(Config::KEY_ENABLED_ON_FAVORITES)
			&& $baseClass === strtolower(ilPersonalDesktopGUI::class)
			&& $a_part === self::TEMPLATE_GET
			&& $a_par["tpl_id"] === self::TEMPLATE_ID_FAVORITES);
	}
}
