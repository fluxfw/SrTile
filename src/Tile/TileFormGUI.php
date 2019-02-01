<?php

namespace srag\Plugins\SrTile\Tile;

use ilCheckboxInputGUI;
use ilColorPickerInputGUI;
use ilException;
use ilFormSectionHeaderGUI;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use ilImageFileInputGUI;
use ilNonEditableValueGUI;
use ilNotifications4PluginsPlugin;
use ilNumberInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\ObjectPropertyFormGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileFormGUI
 *
 * @package srag\Plugins\srTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TileFormGUI extends ObjectPropertyFormGUI {

	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const LANG_MODULE = TileGUI::LANG_MODULE_TILE;
	/**
	 * @var Tile
	 */
	protected $object;


	/**
	 * TileFormGUI constructor
	 *
	 * @param TileGUI $parent
	 * @param Tile    $tile
	 *
	 * @throws ilException
	 */
	public function __construct(TileGUI $parent, Tile $tile) {
		parent::__construct($parent, $tile);

		if (!self::access()->hasWriteAccess(self::tiles()->filterRefId())) {
			throw new ilException("You have no permission to access this page");
		}
	}


	/**
	 * @inheritdoc
	 */
	protected function getValue(/*string*/
		$key) {
		switch ($key) {
			case "image":
				if (!empty($this->object->getImage())) {
					return "./" . $this->object->getProperties()->getImageWebRootRelativePath();
				}
				break;

			default:
				return parent::getValue($key);
		}

		return NULL;
	}


	/**
	 * @inheritdoc
	 */
	protected function initCommands()/*: void*/ {
		$this->addCommandButton(TileGUI::CMD_UPDATE_TILE, $this->txt("submit"), "tile_submit");

		$this->addCommandButton(TileGUI::CMD_CANCEL, $this->txt("cancel"), "tile_cancel");
	}


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		if (file_exists(__DIR__ . "/../../../Notifications4Plugins/vendor/autoload.php")) {
			$Notifications4Plugins = ilNotifications4PluginsPlugin::PLUGIN_NAME;
		} else {
			$Notifications4Plugins = "";
		}

		$this->fields = [
			"tile_enabled" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_DISABLED => (self::tiles()->isTopTile($this->object)
					|| ($parent_tile = self::tiles()->getParentTile($this->object)) !== NULL
					&& $parent_tile->isTileEnabledChildren()),
				self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object)
			],
			"tile_enabled_children" => [
				self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
				self::PROPERTY_REQUIRED => false
			],
			"show_object_tabs" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				]
			],

			"tile" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"background_color_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::COLOR_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("auto_from_image")
					],
					Tile::COLOR_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"background_color" => [
								self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setDefaultColor" => ""
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("background_color")
			],
			"margin_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SIZE_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SIZE_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"margin" => [
								self::PROPERTY_CLASS => ilNumberInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setSuffix" => "px"
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("margin")
			],
			"open_obj_with_one_child_direct" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::OPEN_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::OPEN_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("open_false")
					],
					Tile::OPEN_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("open_true")
					]
				]
			],

			"image_header" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class,
				"setTitle" => $this->txt("image")
			],
			"image" => [
				self::PROPERTY_CLASS => ilImageFileInputGUI::class,
				self::PROPERTY_REQUIRED => false
			],
			"image_position" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::POSITION_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::POSITION_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_top")
					],
					Tile::POSITION_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_bottom")
					]
				],
				"setTitle" => $this->txt("position")
			],
			"show_image_as_background" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				]
			],
			"object_icon_position" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::POSITION_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::POSITION_NONE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_none")
					],
					Tile::POSITION_LEFT_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_left_top")
					],
					Tile::POSITION_LEFT_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_left_bottom")
					],
					Tile::POSITION_RIGHT_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_right_top")
					],
					Tile::POSITION_RIGHT_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_right_bottom")
					]
				]
			],

			"label" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],

			"font_color_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::COLOR_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::COLOR_TYPE_CONTRAST => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("color_contrast")
					],
					Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("auto_from_image")
					],
					Tile::COLOR_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"font_color" => [
								self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setDefaultColor" => ""
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("font_color")
			],
			"font_size_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SIZE_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SIZE_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"font_size" => [
								self::PROPERTY_CLASS => ilNumberInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setSuffix" => "px"
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("font_size")
			],
			"label_horizontal_align" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::HORIZONTAL_ALIGN_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::HORIZONTAL_ALIGN_LEFT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("horizontal_align_left")
					],
					Tile::HORIZONTAL_ALIGN_CENTER => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("horizontal_align_center")
					],
					Tile::HORIZONTAL_ALIGN_RIGHT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("horizontal_align_right")
					]
				],
				"setTitle" => $this->txt("horizontal_align")
			],
			"label_vertical_align" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::VERTICAL_ALIGN_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::VERTICAL_ALIGN_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_top")
					],
					Tile::VERTICAL_ALIGN_CENTER => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_center")
					],
					Tile::VERTICAL_ALIGN_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_bottom")
					]
				],
				"setTitle" => $this->txt("vertical_align")
			],

			"border" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"border_color_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::COLOR_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::COLOR_TYPE_BACKGROUND => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("color_background")
					],
					Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("auto_from_image")
					],
					Tile::COLOR_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"border_color" => [
								self::PROPERTY_CLASS => ilColorPickerInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setDefaultColor" => ""
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("border_color")
			],
			"border_size_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SIZE_TYPE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SIZE_TYPE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"border_size" => [
								self::PROPERTY_CLASS => ilNumberInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								"setSuffix" => "px"
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				"setTitle" => $this->txt("border_size")
			],

			"actions" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"actions_position" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::POSITION_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::POSITION_LEFT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_left")
					],
					Tile::POSITION_RIGHT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_right")
					]
				],
				"setTitle" => $this->txt("position")
			],
			"actions_vertical_align" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::VERTICAL_ALIGN_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::VERTICAL_ALIGN_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_top")
					],
					Tile::VERTICAL_ALIGN_CENTER => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_center")
					],
					Tile::VERTICAL_ALIGN_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("vertical_align_bottom")
					]
				],
				"setTitle" => $this->txt("vertical_align")
			],
			"show_actions" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true_if_permitted")
					]
				]
			],

			"favorites" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"favorites_disabled_hint" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
				self::PROPERTY_VALUE => $this->txt("disabled_hint"),
				self::PROPERTY_NOT_ADD => self::ilias()->favorites(self::dic()->user())->enabled(),
				"setTitle" => ""
			],
			"show_favorites_icon" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				],
				self::PROPERTY_NOT_ADD => (!self::ilias()->favorites(self::dic()->user())->enabled())
			],

			"rating" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"enable_rating" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("disabled")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("enabled")
					]
				]
			],
			"show_likes_count" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				]
			],

			"recommendation" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"recommendation_disabled_hint_" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
				self::PROPERTY_VALUE => self::plugin()
					->translate("recommendation_disabled_hint", self::LANG_MODULE, [ (!empty($Notifications4Plugins) ? $Notifications4Plugins : "Notifications4Plugins") ]),
				self::PROPERTY_NOT_ADD => (!empty($Notifications4Plugins)),
				"setTitle" => ""
			],
			"show_recommend_icon" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				],
				self::PROPERTY_NOT_ADD => empty($Notifications4Plugins)
			],
			"recommend_mail_template_type" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::MAIL_TEMPLATE_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::MAIL_TEMPLATE_SET => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_SUBITEMS => [
							"recommend_mail_template" => [
								self::PROPERTY_CLASS => ilSelectInputGUI::class,
								self::PROPERTY_REQUIRED => false,
								self::PROPERTY_OPTIONS => [ "" => "" ] + self::tiles()->getMailTemplatesText(),
								"setInfo" => (!empty($Notifications4Plugins) ? $Notifications4Plugins : "Notifications4Plugins")
							]
						],
						"setTitle" => $this->txt("set")
					]
				],
				self::PROPERTY_NOT_ADD => empty($Notifications4Plugins),
				"setTitle" => $this->txt("recommend_mail_template")
			],

			"learning_progress" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"learning_progress_disabled_hint" => [
				self::PROPERTY_CLASS => ilNonEditableValueGUI::class,
				self::PROPERTY_VALUE => $this->txt("disabled_hint"),
				self::PROPERTY_NOT_ADD => self::ilias()->learningProgress(self::dic()->user())->enabled(),
				"setTitle" => ""
			],
			"show_learning_progress" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::LEARNING_PROGRESS_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::LEARNING_PROGRESS_NONE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_none")
					],
					Tile::LEARNING_PROGRESS_ICON => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_learning_progress_icon")
					],
					Tile::LEARNING_PROGRESS_BAR => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_learning_progress_bar")
					]
				],
				self::PROPERTY_NOT_ADD => (!self::ilias()->learningProgress(self::dic()->user())->enabled())
			],
			"learning_progress_position" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::POSITION_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::POSITION_LEFT_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_left_top")
					],
					Tile::POSITION_LEFT_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_left_bottom")
					],
					Tile::POSITION_RIGHT_TOP => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_right_top")
					],
					Tile::POSITION_RIGHT_BOTTOM => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("position_right_bottom")
					]
				],
				self::PROPERTY_NOT_ADD => (!self::ilias()->learningProgress(self::dic()->user())->enabled())
			],
			"show_learning_progress_legend" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				],
				self::PROPERTY_NOT_ADD => (!self::ilias()->learningProgress(self::dic()->user())->enabled())
			],

			"preconditions" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
			],
			"show_preconditions" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				]
			],

			"certificate" => [
				self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class,
				self::PROPERTY_NOT_ADD => (!self::ilias()->certificates(self::dic()->user(), $this->object->getObjRefId())->enabled())
			],
			"show_download_certificate" => [
				self::PROPERTY_CLASS => ilRadioGroupInputGUI::class,
				self::PROPERTY_REQUIRED => false,
				self::PROPERTY_SUBITEMS => [
					Tile::SHOW_PARENT => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						self::PROPERTY_NOT_ADD => self::tiles()->isTopTile($this->object),
						"setTitle" => $this->txt("parent")
					],
					Tile::SHOW_FALSE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_false")
					],
					Tile::SHOW_TRUE => [
						self::PROPERTY_CLASS => ilRadioOption::class,
						"setTitle" => $this->txt("show_true")
					]
				],
				self::PROPERTY_NOT_ADD => (!self::ilias()->certificates(self::dic()->user(), $this->object->getObjRefId())->enabled())
			]
		];
	}


	/**
	 * @inheritdoc
	 */
	protected function initId()/*: void*/ {
		$this->setId("tile_form");
	}


	/**
	 * @inheritdoc
	 */
	protected function initTitle()/*: void*/ {
		$this->setTitle(self::plugin()->translate("object", self::LANG_MODULE, [ $this->object->getProperties()->getTitle() ]));
	}


	/**
	 * @inheritdoc
	 */
	protected function storeValue(/*string*/
		$key, $value)/*: void*/ {
		switch ($key) {
			case "image":
				if (!self::dic()->upload()->hasBeenProcessed()) {
					self::dic()->upload()->process();
				}

				/** @var UploadResult $result */
				$result = array_pop(self::dic()->upload()->getResults());

				if ($this->getInput("image_delete") || $result->getSize() > 0) {
					if (!empty($this->object->getImage())) {
						$image_path = $this->object->getProperties()->getImageWebRootRelativePath();
						if (file_exists($image_path)) {
							unlink($image_path);
						}

						$this->object->setImage("");
					}
				}

				if (intval($result->getSize()) === 0) {
					break;
				}

				$file_name = $this->object->getTileId() . "." . pathinfo($result->getName(), PATHINFO_EXTENSION);

				self::dic()->upload()->moveOneFileTo($result, $this->object->getProperties()
					->getImageRelativePath(false), Location::WEB, $file_name, true);

				parent::storeValue($key, $file_name);

				$this->object->getProperties()->getImageDominantColor();
				break;

			case "tile_enabled":
				if ($this->getItemByPostVar($key)->getDisabled()) {
					$value = true;
				}

				parent::storeValue($key, $value);
				break;

			default:
				parent::storeValue($key, $value);
				break;
		}
	}
}
