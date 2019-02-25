<?php

namespace srag\Plugins\SrTile\Template;

use Closure;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Templates
 *
 * @package srag\Plugins\SrTile\Template
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Templates {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const TYPE_OTHER = "_other";
	/**
	 * @var self
	 */
	protected static $instance = NULL;
	/**
	 * @var array
	 */
	protected static $object_types = [
		"cat",
		"crs",
		"grp",
		"fold",
		self::TYPE_OTHER
	];


	/**
	 * @return self
	 */
	public static function getInstance(): self {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * @return array
	 */
	public function getArray(): array {
		return array_map(function (string $object_type): array {
			return [ "template" => $this->getByObjectType($object_type) ];
		}, self::$object_types);
	}


	/**
	 * @param string $object_type
	 *
	 * @return Template
	 */
	public function getByObjectType(string $object_type): Template {
		if (!in_array($object_type, self::$object_types)) {
			return $this->getByObjectType(self::TYPE_OTHER);
		}

		$template = Template::where([
			"object_type" => $object_type
		])->first();

		if ($template === NULL) {
			if ($object_type === self::TYPE_OTHER) {
				$template = $this->getByObjectType(self::TYPE_OTHER)->copy();
			} else {
				$template = new Template();
			}

			$template->setObjectType($object_type);
		}

		return $template;
	}


	/**
	 * @param string $object_type
	 */
	public function overrideTilesWithObjectType(string $object_type)/*: void*/ {
		/**
		 * @var Tile[] $tiles
		 */

		$tiles = array_filter(Tile::get(), function (Tile $tile) use ($object_type) : bool {
			$tile_object_type = self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($tile->getObjRefId()));

			if ($object_type === self::TYPE_OTHER) {
				return !in_array($tile_object_type, self::$object_types);
			} else {
				return ($tile_object_type === $object_type);
			}
		});

		foreach ($tiles as $tile) {
			$this->applyToTile($tile);
		}
	}


	/**
	 * @param Tile $tile
	 */
	public function applyToTile(Tile $tile)/*:void*/ {
		$object_type = strval(self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($tile->getObjRefId())));

		$template = $this->getByObjectType($object_type);

		$properties = Closure::bind(function () {
			return get_object_vars($this);
		}, $template, Template::class)();
		$properties = array_filter($properties, function (string $property): bool {
			return ($property !== "tile_id" && $property !== "obj_ref_id" && $property !== "object_type" && $property !== "il_object"
				&& $property !== "ar_safe_read"
				&& $property !== "connector_container_name");
		}, ARRAY_FILTER_USE_KEY);

		// Delete old image
		$tile->applyNewImage("");

		foreach ($properties as $key => $value) {
			Closure::bind(function ($key, $value) {
				$this->{$key} = $value;
			}, $tile, Tile::class)($key, $value);
		}

		// Copy template image
		$tile->applyNewImage($template->getImagePathWithCheck());

		$tile->store();
	}
}
