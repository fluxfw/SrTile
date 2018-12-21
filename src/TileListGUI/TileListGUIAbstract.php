<?php

namespace srag\Plugins\SrTile\TileListGUI;

use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\TileGUI\TileGUIInterface;
use srag\Plugins\SrTile\TileList\TileListInterface;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileListContainerGUI
 *
 * @package srag\Plugins\SrTile\TileListGUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
abstract class TileListGUIAbstract implements TileListGUIInterface {

	use SrTileTrait;
	use DICTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	/**
	 * @var TileListInterface $tile_list
	 */
	protected $tile_list;


	/**
	 * TileListGUIAbstract constructor
	 *
	 * @param int $id
	 */
	public function __construct(int $id) {
		$list_class = static::LIST_CLASS;

		$this->tile_list = $list_class::getInstance($id);
	}


	/**
	 * @inheritdoc
	 */
	public function render(): string {
		$tile_list_html = "";

		if (count($this->tile_list->getTiles()) > 0) {
			self::dic()->mainTemplate()->addCss(self::plugin()->directory() . "/css/srtile.css");

			$tpl = self::plugin()->template("TileList/tpl.tile_list.html");

			$tpl->setCurrentBlock('row');
			$gui_class = static::GUI_CLASS;
			$tile_html = self::output()->getHTML(array_map(function (Tile $tile) use ($gui_class): TileGUIInterface {
				return new $gui_class($tile);
			}, $this->tile_list->getTiles()));
			$tpl->setVariable("TILES", $tile_html);
			$tpl->parseCurrentBlock();
			$tile_list_html = self::output()->getHTML($tpl);
		}

		$this->hideOriginalRowsOfTiles();

		return $tile_list_html;
	}


	/**
	 * @inheritdoc
	 */
	public function hideOriginalRowsOfTiles(bool $global_layout = true) /*:void*/ {
		$css = '';
		$is_parent_css_rendered = false;
		foreach ($this->tile_list->getTiles() as $tile) {
			$css .= '#sr-tile-' . $tile->getTileId();
			$css .= '{' . $tile->getProperties()->getColor() . $tile->getProperties()->getSize() . '}';
			$css .= '#sr-tile-' . $tile->getTileId() . ' .btn-default';
			$css .= '{' . $tile->getProperties()->getColor(true) . '}';

			if ($global_layout) {
				$css .= '#lg_div_';
				$css .= $tile->getObjRefId();
				$css .= '_pref_';
				$css .= $this->tile_list->getBaseId();
				$css .= '{display:none!important;}';

				if (!$is_parent_css_rendered) {
					$is_parent_css_rendered = true;

					$parent_tile = self::tiles()->getParentTile($tile);
					if ($parent_tile !== NULL) {
						if (!empty($parent_tile->getProperties()->getBackgroundColor())) {
							$css .= 'a#il_mhead_t_focus';
							$css .= '{color:#' . $parent_tile->getProperties()->getBackgroundColor() . '!important;}';
						}

						$css .= '.btn-default';
						$css .= '{' . $tile->getProperties()->getColor();
						if (!empty($parent_tile->getProperties()->getBackgroundColor())) {
							$css .= 'border-color:#' . $parent_tile->getProperties()->getBackgroundColor() . '!important;';
						}
						$css .= '}';
					}
				}
			} else {
				// Favorites
				$css .= '#lg_div_';
				$css .= $tile->getObjRefId();
				$css .= '_pref_';
				$css .= '0';
				$css .= '{display:none!important;}';
			}
		}

		self::dic()->mainTemplate()->addInlineCss($css);
	}
}
