<?php

namespace srag\Plugins\SrTile\TileList\TileListContainer;

use ilContainerSorting;
use srag\Plugins\SrTile\TileList\TileListAbstract;



/**
 * Class TileListContainer
 *
 * @package srag\Plugins\SrTile\TileList\TileListContainer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
class TileListContainer extends TileListAbstract {

	protected static $ignored_obj_types = ['itgr'];

	/**
	 * @inheritdoc
	 */
	public function read(array $items = []) /*:void*/ {
		$items = self::dic()->tree()->getChilds($this->getBaseId());
		parent::read($this->sortItems($items));
	}


	/**
	 * @param array $items
	 *
	 * @see \ilContainer::getSubItems
	 * @return array
	 */
	private function sortItems(array $items = []) {

		$arr_prepared_items = [];
		foreach ($items as $key => $item) {

			if(in_array($item["type"],self::$ignored_obj_types)) {
				continue;
			}

			// group object type groups together (e.g. learning resources)
			$type = self::dic()->objDefinition()->getGroupOfObj($item["type"]);
			if ($type == "") {
				$type = $item["type"];
			}

			$arr_prepared_items[$type][$item['ref_id']] = $item;

			$arr_prepared_items["_all"][$item['ref_id']] = $item;
			if ($item["type"] != "sess") {
				$arr_prepared_items["_non_sess"][$item['ref_id']] = $item;
			}
		}

		$container_sorting = ilContainerSorting::_getInstance($this->getBaseId());
		$sorted_items = $container_sorting->sortItems($arr_prepared_items);

		return $sorted_items['_all'];
	}
}
