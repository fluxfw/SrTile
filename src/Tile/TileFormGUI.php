<?php

namespace srag\Plugins\SrTile\Tile;

use ilColorPickerInputGUI;
use ilFormSectionHeaderGUI;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use ilImageFileInputGUI;
use ilNonEditableValueGUI;
use ilNumberInputGUI;
use ilRadioGroupInputGUI;
use ilRadioOption;
use ilSelectInputGUI;
use ilSrTilePlugin;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\Items\Items;
use srag\CustomInputGUIs\SrTile\PropertyFormGUI\PropertyFormGUI;
use srag\Notifications4Plugin\SrTile\Notification\NotificationInterface;
use srag\Notifications4Plugin\SrTile\Notification\NotificationsCtrl;
use srag\Plugins\SrTile\Template\Template;
use srag\Plugins\SrTile\Template\TemplateConfigGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileFormGUI
 *
 * @package srag\Plugins\srTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class TileFormGUI extends PropertyFormGUI
{

    use SrTileTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const LANG_MODULE = TileGUI::LANG_MODULE;
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * TileFormGUI constructor
     *
     * @param TileGUI|TemplateConfigGUI $parent
     * @param Tile                      $tile
     */
    public function __construct($parent, Tile $tile)
    {
        $this->tile = $tile;

        parent::__construct($parent);
    }


    /**
     * @inheritDoc
     */
    protected function getValue(/*string*/ $key)
    {
        switch ($key) {
            case "columns_count":
                if ($this->tile->getColumnsType() === Tile::SIZE_TYPE_COUNT) {
                    return Items::getter($this->tile, "columns");
                }
                break;

            case "columns_fix_width":
                if ($this->tile->getColumnsType() === Tile::SIZE_TYPE_PX) {
                    return Items::getter($this->tile, "columns");
                }
                break;

            case "image":
                if (!empty(Items::getter($this->tile, $key))) {
                    return "./" . $this->tile->getImagePath();
                }
                break;

            default:
                return Items::getter($this->tile, $key);
        }

        return null;
    }


    /**
     * @inheritDoc
     */
    protected function initCommands()/*: void*/
    {
        $this->addCommandButton(TileGUI::CMD_UPDATE_TILE, $this->txt("save"));
    }


    /**
     * @inheritDoc
     */
    protected function initFields()/*: void*/
    {
        $this->fields = [
            "view"                        => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::VIEW_DISABLED => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("view_disabled")
                    ],
                    Tile::VIEW_TILE     => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "columns_type" => [
                                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                self::PROPERTY_SUBITEMS => [
                                    Tile::SIZE_TYPE_COUNT => [
                                        self::PROPERTY_CLASS    => ilRadioOption::class,
                                        self::PROPERTY_SUBITEMS => [
                                            "columns_count" => [
                                                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                                self::PROPERTY_REQUIRED => false,
                                                "setTitle"              => $this->txt("columns_count")
                                            ]
                                        ],
                                        "setTitle"              => $this->txt("columns_count")
                                    ],
                                    Tile::SIZE_TYPE_PX    => [
                                        self::PROPERTY_CLASS    => ilRadioOption::class,
                                        self::PROPERTY_SUBITEMS => [
                                            "columns_fix_width" => [
                                                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                                self::PROPERTY_REQUIRED => false,
                                                "setTitle"              => $this->txt("columns_fix_width"),
                                                "setSuffix"             => "px"
                                            ]
                                        ],
                                        "setTitle"              => $this->txt("columns_fix_width")
                                    ]
                                ],
                                "setTitle"              => $this->txt("columns")
                            ]
                        ],
                        "setTitle"              => $this->txt("view_tile")
                    ],
                    Tile::VIEW_LIST     => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("view_list")
                    ]
                ]
            ],
            "margin_type"                 => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SIZE_TYPE_PX => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "margin" => [
                                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setSuffix"             => "px"
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("margin")
            ],
            "show_object_tabs"            => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],
            "apply_colors_to_global_skin" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],

            "tile"                           => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "background_color_type"          => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("color_auto_from_image")
                    ],
                    Tile::COLOR_TYPE_SET             => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "background_color" => [
                                self::PROPERTY_CLASS    => ilColorPickerInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setDefaultColor"       => ""
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("background_color")
            ],
            "shadow"                         => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],
            "open_obj_with_one_child_direct" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::OPEN_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("open_false")
                    ],
                    Tile::OPEN_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("open_true")
                    ]
                ]
            ],

            "image_header"             => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class,
                "setTitle"           => $this->txt("image")
            ],
            "image"                    => [
                self::PROPERTY_CLASS    => ilImageFileInputGUI::class,
                self::PROPERTY_REQUIRED => false
            ],
            "image_position"           => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::POSITION_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_top")
                    ],
                    Tile::POSITION_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_bottom")
                    ]
                ],
                "setTitle"              => $this->txt("position")
            ],
            "show_image_as_background" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],
            "object_icon_position"     => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::POSITION_NONE         => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_none")
                    ],
                    Tile::POSITION_LEFT_TOP     => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_top")
                    ],
                    Tile::POSITION_LEFT_BOTTOM  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_bottom")
                    ],
                    Tile::POSITION_RIGHT_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_top")
                    ],
                    Tile::POSITION_RIGHT_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_bottom")
                    ]
                ]
            ],

            "label" => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],

            "font_color_type"        => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::COLOR_TYPE_CONTRAST        => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("color_contrast")
                    ],
                    Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("color_auto_from_image")
                    ],
                    Tile::COLOR_TYPE_SET             => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "font_color" => [
                                self::PROPERTY_CLASS    => ilColorPickerInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setDefaultColor"       => ""
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("font_color")
            ],
            "font_size_type"         => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SIZE_TYPE_PX => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "font_size" => [
                                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setSuffix"             => "px"
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("font_size")
            ],
            "label_horizontal_align" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::HORIZONTAL_ALIGN_LEFT   => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("horizontal_align_left")
                    ],
                    Tile::HORIZONTAL_ALIGN_CENTER => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("horizontal_align_center")
                    ],
                    Tile::HORIZONTAL_ALIGN_RIGHT  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("horizontal_align_right")
                    ]
                ],
                "setTitle"              => $this->txt("horizontal_align")
            ],
            "label_vertical_align"   => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::VERTICAL_ALIGN_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_top")
                    ],
                    Tile::VERTICAL_ALIGN_CENTER => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_center")
                    ],
                    Tile::VERTICAL_ALIGN_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_bottom")
                    ]
                ],
                "setTitle"              => $this->txt("vertical_align")
            ],

            "border"            => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "border_color_type" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::COLOR_TYPE_BACKGROUND      => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("color_background")
                    ],
                    Tile::COLOR_TYPE_AUTO_FROM_IMAGE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("color_auto_from_image")
                    ],
                    Tile::COLOR_TYPE_SET             => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "border_color" => [
                                self::PROPERTY_CLASS    => ilColorPickerInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setDefaultColor"       => ""
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("border_color")
            ],
            "border_size_type"  => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SIZE_TYPE_PX => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS => [
                            "border_size" => [
                                self::PROPERTY_CLASS    => ilNumberInputGUI::class,
                                self::PROPERTY_REQUIRED => false,
                                "setSuffix"             => "px"
                            ]
                        ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("border_size")
            ],

            "actions"                => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "actions_position"       => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::POSITION_LEFT  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left")
                    ],
                    Tile::POSITION_RIGHT => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right")
                    ]
                ],
                "setTitle"              => $this->txt("position")
            ],
            "actions_vertical_align" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::VERTICAL_ALIGN_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_top")
                    ],
                    Tile::VERTICAL_ALIGN_CENTER => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_center")
                    ],
                    Tile::VERTICAL_ALIGN_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("vertical_align_bottom")
                    ]
                ],
                "setTitle"              => $this->txt("vertical_align")
            ],
            "show_actions"           => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_ACTIONS_NONE                        => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_ACTIONS_ONLY_WITH_WRITE_PERMISSIONS => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_only_write_permissions")
                    ],
                    Tile::SHOW_ACTIONS_ALWAYS                      => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],

            "online_status"           => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "show_online_status_icon" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],

            "favorites"               => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "favorites_disabled_hint" => [
                self::PROPERTY_CLASS   => ilNonEditableValueGUI::class,
                self::PROPERTY_VALUE   => $this->txt("disabled_hint"),
                self::PROPERTY_NOT_ADD => self::srTile()->favorites(self::dic()->user())->enabled(),
                "setTitle"             => ""
            ],
            "show_favorites_icon"     => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->favorites(self::dic()->user())->enabled())
            ],

            "rating"           => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "enable_rating"    => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("disabled")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("enabled")
                    ]
                ]
            ],
            "show_likes_count" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],

            "recommendation"               => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "show_recommend_icon"          => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],
            "recommend_mail_template_type" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::MAIL_TEMPLATE_SET => [
                        self::PROPERTY_CLASS    => ilRadioOption::class,
                        self::PROPERTY_SUBITEMS =>
                            [
                                "recommend_mail_template" => [
                                    self::PROPERTY_CLASS    => ilSelectInputGUI::class,
                                    self::PROPERTY_REQUIRED => false,
                                    self::PROPERTY_OPTIONS  => ["" => ""] + array_combine(array_map(function (NotificationInterface $notification) : string {
                                            return $notification->getName();
                                        }, self::srTile()->notifications4plugin()->notifications()
                                            ->getNotifications()), array_map(function (NotificationInterface $notification) : string {
                                            return $notification->getTitle();
                                        }, self::srTile()->notifications4plugin()->notifications()
                                            ->getNotifications())),
                                    "setTitle"              => self::plugin()->translate("template_selection", NotificationsCtrl::LANG_MODULE)
                                ]
                            ],
                        "setTitle"              => $this->txt("set")
                    ]
                ],
                "setTitle"              => $this->txt("recommend_mail_template")
            ],

            "learning_progress"               => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "learning_progress_disabled_hint" => [
                self::PROPERTY_CLASS   => ilNonEditableValueGUI::class,
                self::PROPERTY_VALUE   => $this->txt("disabled_hint"),
                self::PROPERTY_NOT_ADD => self::srTile()->ilias()->learningProgress(self::dic()->user())->enabled(),
                "setTitle"             => ""
            ],
            "show_learning_progress"          => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::LEARNING_PROGRESS_NONE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_none")
                    ],
                    Tile::LEARNING_PROGRESS_ICON  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_learning_progress_icon")
                    ],
                    Tile::LEARNING_PROGRESS_METER => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_learning_progress_meter")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->ilias()->learningProgress(self::dic()->user())->enabled())
            ],
            "learning_progress_position"      => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::POSITION_LEFT_TOP     => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_top")
                    ],
                    Tile::POSITION_LEFT_BOTTOM  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_bottom")
                    ],
                    Tile::POSITION_RIGHT_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_top")
                    ],
                    Tile::POSITION_RIGHT_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_bottom")
                    ],
                    Tile::POSITION_ON_THE_ICONS => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_on_the_icons")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->ilias()->learningProgress(self::dic()->user())->enabled())
            ],
            "show_learning_progress_legend"   => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->ilias()->learningProgress(self::dic()->user())->enabled())
            ],
            "show_learning_progress_filter"   => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->ilias()->learningProgress(self::dic()->user())->enabled())
            ],

            "preconditions"      => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "show_preconditions" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],

            "certificate"               => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "certificate_hint"          => [
                self::PROPERTY_CLASS   => ilNonEditableValueGUI::class,
                self::PROPERTY_VALUE   => $this->txt("disabled_hint"),
                self::PROPERTY_NOT_ADD => self::srTile()->ilias()->certificates(self::dic()->user(), $this->tile)->enabled(),
                "setTitle"             => ""
            ],
            "show_download_certificate" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ],
                self::PROPERTY_NOT_ADD  => (!self::srTile()->ilias()->certificates(self::dic()->user(), $this->tile)->enabled())
            ],

            "language"               => [
                self::PROPERTY_CLASS => ilFormSectionHeaderGUI::class
            ],
            "show_language_flag"     => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::SHOW_FALSE => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_false")
                    ],
                    Tile::SHOW_TRUE  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("show_true")
                    ]
                ]
            ],
            "language_flag_position" => [
                self::PROPERTY_CLASS    => ilRadioGroupInputGUI::class,
                self::PROPERTY_REQUIRED => false,
                self::PROPERTY_SUBITEMS => [
                    Tile::POSITION_LEFT_TOP     => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_top")
                    ],
                    Tile::POSITION_LEFT_BOTTOM  => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_left_bottom")
                    ],
                    Tile::POSITION_RIGHT_TOP    => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_top")
                    ],
                    Tile::POSITION_RIGHT_BOTTOM => [
                        self::PROPERTY_CLASS => ilRadioOption::class,
                        "setTitle"           => $this->txt("position_right_bottom")
                    ]
                ],
                "setTitle"              => $this->txt("position")
            ]
        ];
    }


    /**
     * @inheritDoc
     */
    protected function initId()/*: void*/
    {
        $this->setId("tile_form");
    }


    /**
     * @inheritDoc
     */
    protected function initTitle()/*: void*/
    {
        $this->setTitle($this->tile->_getTitle());
    }


    /**
     * @inheritDoc
     */
    public function storeForm() : bool
    {
        if (empty($this->tile->getTileId())) {
            self::srTile()->tiles()->storeTile($this->tile);
        }

        if (!parent::storeForm()) {
            return false;
        }

        self::srTile()->tiles()->storeTile($this->tile);

        return true;
    }


    /**
     * @inheritDoc
     */
    public function checkInput() : bool
    {
        if (intval(filter_input(INPUT_POST, "view") === Tile::VIEW_DISABLED)) {
            // Allows incomplete configuration if the tile is disabled
            parent::checkInput();

            return true;
        } else {
            return parent::checkInput();
        }
    }


    /**
     * @inheritDoc
     */
    protected function storeValue(/*string*/ $key, $value)/*: void*/
    {
        switch ($key) {
            case "columns_count":
                if ($this->tile->getColumnsType() === Tile::SIZE_TYPE_COUNT) {
                    Items::setter($this->tile, "columns", $value);
                }
                break;

            case "columns_fix_width":
                if ($this->tile->getColumnsType() === Tile::SIZE_TYPE_PX) {
                    Items::setter($this->tile, "columns", $value);
                }
                break;

            case "image":
                if (!self::dic()->upload()->hasBeenProcessed()) {
                    self::dic()->upload()->process();
                }

                /** @var UploadResult $result */
                $result = array_pop(self::dic()->upload()->getResults());

                if ($this->getInput("image_delete") || $result->getSize() > 0) {
                    $this->tile->applyNewImage("");
                }

                if (intval($result->getSize()) === 0) {
                    break;
                }

                $file_name = $this->tile->getTileId() . "." . pathinfo($result->getName(), PATHINFO_EXTENSION);

                self::dic()->upload()->moveOneFileTo($result, $this->tile->getImagePathAsRelative(false), Location::WEB, $file_name, true);

                Items::setter($this->tile, $key, $file_name);

                $this->tile->_getImageDominantColor();
                break;

            default:
                Items::setter($this->tile, $key, $value);
                break;
        }
    }
}
