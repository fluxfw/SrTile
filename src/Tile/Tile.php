<?php

namespace srag\Plugins\SrTile\Tile;

use ActiveRecord;
use arConnector;
use ColorThief\ColorThief;
use ilLink;
use ilLinkResourceItems;
use ilObject;
use ilObjectFactory;
use ilObjSAHSLearningModule;
use ilObjSCORMLearningModuleGUI;
use ilSAHSPresentationGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Tile
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Tile extends ActiveRecord
{

    use DICTrait;
    use SrTileTrait;
    const TABLE_NAME = "ui_uihk_srtile_tile";
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const IMAGE_PREFIX = "tile_";
    const COLOR_BLACK = "0,0,0";
    const COLOR_WHITE = "255,255,255";
    const SHOW_IMAGE_AS_BACKGROUND_COLOR_ALPHA = 0.6;
    const COLOR_TYPE_SET = 1;
    const COLOR_TYPE_CONTRAST = 2;
    const COLOR_TYPE_BACKGROUND = 3;
    const COLOR_TYPE_AUTO_FROM_IMAGE = 4;
    /**
     * @var int
     *
     * @deprecated
     */
    const COLOR_TYPE_PARENT = 5;
    const SIZE_TYPE_PX = 1;
    const SIZE_TYPE_COUNT = 2;
    /**
     * @var int
     *
     * @deprecated
     */
    const SIZE_TYPE_PARENT = 2;
    const POSITION_TOP = 1;
    const POSITION_BOTTOM = 2;
    const POSITION_LEFT = 3;
    const POSITION_RIGHT = 4;
    const POSITION_LEFT_TOP = 5;
    const POSITION_LEFT_BOTTOM = 6;
    const POSITION_RIGHT_TOP = 7;
    const POSITION_RIGHT_BOTTOM = 8;
    const POSITION_ON_THE_ICONS = 11;
    const POSITION_NONE = 9;
    /**
     * @var int
     *
     * @deprecated
     */
    const POSITION_PARENT = 10;
    const HORIZONTAL_ALIGN_LEFT = 1;
    const HORIZONTAL_ALIGN_CENTER = 2;
    const HORIZONTAL_ALIGN_RIGHT = 3;
    /**
     * @var int
     *
     * @deprecated
     */
    const HORIZONTAL_ALIGN_PARENT = 4;
    const VERTICAL_ALIGN_TOP = 1;
    const VERTICAL_ALIGN_CENTER = 2;
    const VERTICAL_ALIGN_BOTTOM = 3;
    /**
     * @var int
     *
     * @deprecated
     */
    const VERTICAL_ALIGN_PARENT = 4;
    const SHOW_FALSE = 1;
    const SHOW_TRUE = 2;
    /**
     * @var int
     *
     * @deprecated
     */
    const SHOW_PARENT = 3;
    const MAIL_TEMPLATE_SET = 1;
    /**
     * @var int
     *
     * @deprecated
     */
    const MAIL_TEMPLATE_PARENT = 2;
    const LEARNING_PROGRESS_ICON = 1;
    const LEARNING_PROGRESS_METER = 2;
    const LEARNING_PROGRESS_NONE = 3;
    /**
     * @var int
     *
     * @deprecated
     */
    const LEARNING_PROGRESS_PARENT = 4;
    const OPEN_FALSE = 1;
    const OPEN_TRUE = 2;
    /**
     * @var int
     *
     * @deprecated
     */
    const OPEN_PARENT = 3;
    const VIEW_TILE = 1;
    const VIEW_LIST = 2;
    /**
     * @var int
     *
     * @deprecated
     */
    const VIEW_PARENT = 3;
    const VIEW_DISABLED = 4;
    const DEFAULT_ACTIONS_POSITION = self::POSITION_RIGHT;
    const DEFAULT_ACTIONS_VERTICAL_ALIGN = self::VERTICAL_ALIGN_BOTTOM;
    const DEFAULT_APPLY_COLORS_TO_GLOBAL_SKIN = Tile::SHOW_FALSE;
    const DEFAULT_BACKGROUND_COLOR_TYPE = self::COLOR_TYPE_SET;
    const DEFAULT_BORDER_SIZE = 0;
    const DEFAULT_BORDER_SIZE_TYPE = self::SIZE_TYPE_PX;
    const DEFAULT_BORDER_COLOR_TYPE = self::COLOR_TYPE_BACKGROUND;
    const DEFAULT_COLUMNS = 400;
    const DEFAULT_COLUMNS_TYPE = self::SIZE_TYPE_PX;
    const DEFAULT_ENABLE_RATING = Tile::SHOW_FALSE;
    const DEFAULT_FONT_COLOR_TYPE = self::COLOR_TYPE_CONTRAST;
    const DEFAULT_FONT_SIZE = 16;
    const DEFAULT_FONT_SIZE_TYPE = self::SIZE_TYPE_PX;
    const DEFAULT_IMAGE_POSITION = self::POSITION_TOP;
    const DEFAULT_LABEL_HORIZONTAL_ALIGN = self::HORIZONTAL_ALIGN_LEFT;
    const DEFAULT_LABEL_VERTICAL_ALIGN = self::VERTICAL_ALIGN_TOP;
    const DEFAULT_LANGUAGE_FLAG_POSITION = Tile::POSITION_RIGHT_TOP;
    const DEFAULT_LEARNING_PROGRESS_POSITION = Tile::POSITION_LEFT_TOP;
    const DEFAULT_MARGIN = 10;
    const DEFAULT_MARGIN_TYPE = self::SIZE_TYPE_PX;
    const DEFAULT_OBJECT_ICON_POSITION = Tile::POSITION_LEFT_BOTTOM;
    const DEFAULT_OPEN_OBJ_WITH_ONE_CHILD_DIRECT = Tile::OPEN_FALSE;
    const DEFAULT_RECOMMENDATION_MAIL_TEMPLATE_TYPE = Tile::MAIL_TEMPLATE_SET;
    const DEFAULT_SHADOW = Tile::SHOW_TRUE;
    const DEFAULT_SHOW_ACTIONS = Tile::SHOW_TRUE;
    const DEFAULT_SHOW_DOWNLOAD_CERTIFICATE = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_FAVORITES_ICON = Tile::SHOW_TRUE;
    const DEFAULT_SHOW_IMAGE_AS_BACKGROUND = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_LANGUAGE_FLAG = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_LEARNING_PROGRESS = Tile::LEARNING_PROGRESS_NONE;
    const DEFAULT_SHOW_LEARNING_PROGRESS_FILTER = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_LEARNING_PROGRESS_LEGEND = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_LIKES_COUNT = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_PRECONDITIONS = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_RECOMMEND_ICON = Tile::SHOW_FALSE;
    const DEFAULT_SHOW_OBJECT_TABS = Tile::SHOW_TRUE;
    const DEFAULT_SHOW_TITLE = Tile::SHOW_TRUE;
    const DEFAULT_VIEW = Tile::VIEW_TILE;


    /**
     * @param int|null $obj_ref_id
     *
     * @return int|null
     */
    public static function modifyTileRefIdForRead(int $obj_ref_id = null)/*: ?int*/
    {
        return $obj_ref_id;
    }


    /**
     * @var ilObject|null
     */
    protected $il_object = null;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $tile_id;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_length      8
     * @con_is_notnull  true
     */
    protected $obj_ref_id;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_length      256
     * @con_is_notnull  true
     */
    protected $image = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $background_color_type = self::DEFAULT_BACKGROUND_COLOR_TYPE;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $background_color = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $font_color_type = self::DEFAULT_FONT_COLOR_TYPE;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $font_color = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $margin_type = self::DEFAULT_MARGIN_TYPE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $margin = self::DEFAULT_MARGIN;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $font_size_type = self::DEFAULT_FONT_SIZE_TYPE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $font_size = self::DEFAULT_FONT_SIZE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $image_position = self::DEFAULT_IMAGE_POSITION;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $label_horizontal_align = self::DEFAULT_LABEL_HORIZONTAL_ALIGN;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $label_vertical_align = self::DEFAULT_LABEL_VERTICAL_ALIGN;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $actions_position = self::DEFAULT_ACTIONS_POSITION;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $actions_vertical_align = self::DEFAULT_ACTIONS_VERTICAL_ALIGN;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $object_icon_position = self::DEFAULT_OBJECT_ICON_POSITION;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_favorites_icon = self::DEFAULT_SHOW_FAVORITES_ICON;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_actions = self::DEFAULT_SHOW_ACTIONS;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_title = self::DEFAULT_SHOW_TITLE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $enable_rating = self::DEFAULT_ENABLE_RATING;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_likes_count = self::DEFAULT_SHOW_LIKES_COUNT;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_recommend_icon = self::DEFAULT_SHOW_RECOMMEND_ICON;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $recommend_mail_template_type = self::DEFAULT_RECOMMENDATION_MAIL_TEMPLATE_TYPE;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $recommend_mail_template = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_learning_progress = self::DEFAULT_SHOW_LEARNING_PROGRESS;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $learning_progress_position = self::DEFAULT_LEARNING_PROGRESS_POSITION;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_learning_progress_legend = self::DEFAULT_SHOW_LEARNING_PROGRESS_LEGEND;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $border_size_type = self::DEFAULT_BORDER_SIZE_TYPE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $border_size = self::DEFAULT_BORDER_SIZE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $border_color_type = self::DEFAULT_BORDER_COLOR_TYPE;
    /**
     * @var string
     *
     * @con_has_field   true
     * @con_fieldtype   text
     * @con_is_notnull  true
     */
    protected $border_color = "";
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $open_obj_with_one_child_direct = self::DEFAULT_OPEN_OBJ_WITH_ONE_CHILD_DIRECT;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_image_as_background = self::DEFAULT_SHOW_IMAGE_AS_BACKGROUND;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_preconditions = self::DEFAULT_SHOW_PRECONDITIONS;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_download_certificate = self::DEFAULT_SHOW_DOWNLOAD_CERTIFICATE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_object_tabs = self::DEFAULT_SHOW_OBJECT_TABS;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $view = self::DEFAULT_VIEW;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $shadow = self::DEFAULT_SHADOW;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_learning_progress_filter = self::DEFAULT_SHOW_LEARNING_PROGRESS_FILTER;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $apply_colors_to_global_skin = self::DEFAULT_APPLY_COLORS_TO_GLOBAL_SKIN;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $columns_type = self::DEFAULT_COLUMNS_TYPE;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $columns = self::DEFAULT_COLUMNS;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $show_language_flag = self::DEFAULT_SHOW_LANGUAGE_FLAG;
    /**
     * @var int
     *
     * @con_has_field   true
     * @con_fieldtype   integer
     * @con_is_notnull  true
     */
    protected $language_flag_position = self::DEFAULT_LANGUAGE_FLAG_POSITION;


    /**
     * Tile constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @return string
     */
    public function getConnectorContainerName() : string
    {
        return static::TABLE_NAME;
    }


    /**
     * @param string $field_name
     *
     * @return mixed|null
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            default:
                return null;
        }
    }


    /**
     * @param string $field_name
     * @param mixed  $field_value
     *
     * @return mixed|null
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "actions_position":
            case "actions_vertical_align":
            case "apply_colors_to_global_skin":
            case "background_color_type":
            case "border_color_type":
            case "border_size":
            case "border_size_type":
            case "columns":
            case "columns_type":
            case "enable_rating":
            case "font_color_type":
            case "font_size":
            case "font_size_type":
            case "image_position":
            case "label_horizontal_align":
            case "label_vertical_align":
            case "language_flag_position":
            case "learning_progress_position":
            case "margin":
            case "margin_type":
            case "object_icon_position":
            case "obj_ref_id":
            case "open_obj_with_one_child_direct":
            case "recommend_mail_template_type":
            case "shadow":
            case "show_actions":
            case "show_download_certificate":
            case "show_favorites_icon":
            case "show_image_as_background":
            case "show_language_flag":
            case "show_likes_count":
            case "show_learning_progress":
            case "show_learning_progress_filter":
            case "show_learning_progress_legend":
            case "show_object_tabs":
            case "show_preconditions":
            case "show_recommend_icon":
            case "show_title":
            case "tile_id":
            case "view":
                return intval($field_value);

            default:
                return null;
        }
    }


    /**
     * @return int
     */
    public function getTileId() : int
    {
        return $this->tile_id;
    }


    /**
     * @param int $tile_id
     */
    public function setTileId(int $tile_id)/*: void*/
    {
        $this->tile_id = $tile_id;
    }


    /**
     * @return int
     */
    public function getObjRefId()
    {
        return $this->obj_ref_id;
    }


    /**
     * @param int $obj_ref_id
     */
    public function setObjRefId(int $obj_ref_id)/*: void*/
    {
        $this->obj_ref_id = $obj_ref_id;
    }


    /**
     * @return string
     *
     */
    public function getImage() : string
    {
        return $this->image;
    }


    /**
     * @param string $image
     */
    public function setImage(string $image)/*: void*/
    {
        $this->image = $image;
    }


    /**
     * @return int
     */
    public function getBackgroundColorType() : int
    {
        return $this->background_color_type;
    }


    /**
     * @param int $background_color_type
     */
    public function setBackgroundColorType(int $background_color_type)/*: void*/
    {
        $this->background_color_type = $background_color_type;
    }


    /**
     * @return string
     */
    public function getBackgroundColor() : string
    {
        return $this->background_color;
    }


    /**
     * @param string $background_color
     */
    public function setBackgroundColor(string $background_color)/*: void*/
    {
        $this->background_color = $background_color;
    }


    /**
     * @return int
     */
    public function getFontColorType() : int
    {
        return $this->font_color_type;
    }


    /**
     * @param int $font_color_type
     */
    public function setFontColorType(int $font_color_type)/*: void*/
    {
        $this->font_color_type = $font_color_type;
    }


    /**
     * @return string
     */
    public function getFontColor() : string
    {
        return $this->font_color;
    }


    /**
     * @param string $font_color
     */
    public function setFontColor(string $font_color)/*: void*/
    {
        $this->font_color = $font_color;
    }


    /**
     * @return int
     */
    public function getMarginType() : int
    {
        return $this->margin_type;
    }


    /**
     * @param int $margin_type
     */
    public function setMarginType(int $margin_type)/*: void*/
    {
        $this->margin_type = $margin_type;
    }


    /**
     * @return int
     */
    public function getMargin() : int
    {
        return $this->margin;
    }


    /**
     * @param int $margin
     */
    public function setMargin(int $margin)/*: void*/
    {
        $this->margin = $margin;
    }


    /**
     * @return int
     */
    public function getFontSizeType() : int
    {
        return $this->font_size_type;
    }


    /**
     * @param int $font_size_type
     */
    public function setFontSizeType(int $font_size_type)/*: void*/
    {
        $this->font_size_type = $font_size_type;
    }


    /**
     * @return int
     */
    public function getFontSize() : int
    {
        return $this->font_size;
    }


    /**
     * @param int $font_size
     */
    public function setFontSize(int $font_size)/*: void*/
    {
        $this->font_size = $font_size;
    }


    /**
     * @return int
     */
    public function getImagePosition() : int
    {
        return $this->image_position;
    }


    /**
     * @param int $image_position
     */
    public function setImagePosition(int $image_position)/*: void*/
    {
        $this->image_position = $image_position;
    }


    /**
     * @return int
     */
    public function getLabelHorizontalAlign() : int
    {
        return $this->label_horizontal_align;
    }


    /**
     * @param int $label_horizontal_align
     */
    public function setLabelHorizontalAlign(int $label_horizontal_align)/*: void*/
    {
        $this->label_horizontal_align = $label_horizontal_align;
    }


    /**
     * @return int
     */
    public function getLabelVerticalAlign() : int
    {
        return $this->label_vertical_align;
    }


    /**
     * @param int $label_vertical_align
     */
    public function setLabelVerticalAlign(int $label_vertical_align)/*: void*/
    {
        $this->label_vertical_align = $label_vertical_align;
    }


    /**
     * @return int
     */
    public function getActionsPosition() : int
    {
        return $this->actions_position;
    }


    /**
     * @param int $actions_position
     */
    public function setActionsPosition(int $actions_position)/*: void*/
    {
        $this->actions_position = $actions_position;
    }


    /**
     * @return int
     */
    public function getActionsVerticalAlign() : int
    {
        return $this->actions_vertical_align;
    }


    /**
     * @param int $actions_vertical_align
     */
    public function setActionsVerticalAlign(int $actions_vertical_align)/*: void*/
    {
        $this->actions_vertical_align = $actions_vertical_align;
    }


    /**
     * @return int
     */
    public function getObjectIconPosition() : int
    {
        return $this->object_icon_position;
    }


    /**
     * @param int $object_icon_position
     */
    public function setObjectIconPosition(int $object_icon_position)/*: void*/
    {
        $this->object_icon_position = $object_icon_position;
    }


    /**
     * @return int
     */
    public function getShowFavoritesIcon() : int
    {
        return $this->show_favorites_icon;
    }


    /**
     * @param int $show_favorites_icon
     */
    public function setShowFavoritesIcon(int $show_favorites_icon)/*: void*/
    {
        $this->show_favorites_icon = $show_favorites_icon;
    }


    /**
     * @return int
     */
    public function getShowActions() : int
    {
        return $this->show_actions;
    }


    /**
     * @param int $show_actions
     */
    public function setShowActions(int $show_actions)/*: void*/
    {
        $this->show_actions = $show_actions;
    }


    /**
     * @return int
     */
    public function getShowTitle() : int
    {
        return $this->show_title;
    }


    /**
     * @param int $show_title
     */
    public function setShowTitle(int $show_title)/*: void*/
    {
        $this->show_title = $show_title;
    }


    /**
     * @return int
     */
    public function getEnableRating() : int
    {
        return $this->enable_rating;
    }


    /**
     * @param int $enable_rating
     */
    public function setEnableRating(int $enable_rating)/*: void*/
    {
        $this->enable_rating = $enable_rating;
    }


    /**
     * @return int
     */
    public function getShowLikesCount() : int
    {
        return $this->show_likes_count;
    }


    /**
     * @param int $show_likes_count
     */
    public function setShowLikesCount(int $show_likes_count)/*: void*/
    {
        $this->show_likes_count = $show_likes_count;
    }


    /**
     * @return int
     */
    public function getShowRecommendIcon() : int
    {
        return $this->show_recommend_icon;
    }


    /**
     * @param int $show_recommend_icon
     */
    public function setShowRecommendIcon(int $show_recommend_icon)/*: void*/
    {
        $this->show_recommend_icon = $show_recommend_icon;
    }


    /**
     * @return int
     */
    public function getRecommendMailTemplateType() : int
    {
        return $this->recommend_mail_template_type;
    }


    /**
     * @param int $recommend_mail_template_type
     */
    public function setRecommendMailTemplateType(int $recommend_mail_template_type)/*: void*/
    {
        $this->recommend_mail_template_type = $recommend_mail_template_type;
    }


    /**
     * @return string
     */
    public function getRecommendMailTemplate() : string
    {
        return $this->recommend_mail_template;
    }


    /**
     * @param string $recommend_mail_template
     */
    public function setRecommendMailTemplate(string $recommend_mail_template)/*: void*/
    {
        $this->recommend_mail_template = $recommend_mail_template;
    }


    /**
     * @return int
     */
    public function getShowLearningProgress() : int
    {
        return $this->show_learning_progress;
    }


    /**
     * @param int $show_learning_progress
     */
    public function setShowLearningProgress(int $show_learning_progress)/*: void*/
    {
        $this->show_learning_progress = $show_learning_progress;
    }


    /**
     * @return int
     */
    public function getLearningProgressPosition() : int
    {
        return $this->learning_progress_position;
    }


    /**
     * @param int $learning_progress_position
     */
    public function setLearningProgressPosition(int $learning_progress_position)/*: void*/
    {
        $this->learning_progress_position = $learning_progress_position;
    }


    /**
     * @return int
     */
    public function getShowLearningProgressLegend() : int
    {
        return $this->show_learning_progress_legend;
    }


    /**
     * @param int $show_learning_progress_legend
     */
    public function setShowLearningProgressLegend(int $show_learning_progress_legend)/*: void*/
    {
        $this->show_learning_progress_legend = $show_learning_progress_legend;
    }


    /**
     * @return int
     */
    public function getBorderSizeType() : int
    {
        return $this->border_size_type;
    }


    /**
     * @param int $border_size_type
     */
    public function setBorderSizeType(int $border_size_type)/*: void*/
    {
        $this->border_size_type = $border_size_type;
    }


    /**
     * @return int
     */
    public function getBorderSize() : int
    {
        return $this->border_size;
    }


    /**
     * @param int $border_size
     */
    public function setBorderSize(int $border_size)/*: void*/
    {
        $this->border_size = $border_size;
    }


    /**
     * @return int
     */
    public function getBorderColorType() : int
    {
        return $this->border_color_type;
    }


    /**
     * @param int $border_color_type
     */
    public function setBorderColorType(int $border_color_type)/*: void*/
    {
        $this->border_color_type = $border_color_type;
    }


    /**
     * @return string
     */
    public function getBorderColor() : string
    {
        return $this->border_color;
    }


    /**
     * @param string $border_color
     */
    public function setBorderColor(string $border_color)/*: void*/
    {
        $this->border_color = $border_color;
    }


    /**
     * @return int
     */
    public function getOpenObjWithOneChildDirect() : int
    {
        return $this->open_obj_with_one_child_direct;
    }


    /**
     * @param int $open_obj_with_one_child_direct
     */
    public function setOpenObjWithOneChildDirect(int $open_obj_with_one_child_direct)/*: void*/
    {
        $this->open_obj_with_one_child_direct = $open_obj_with_one_child_direct;
    }


    /**
     * @return int
     */
    public function getShowImageAsBackground() : int
    {
        return $this->show_image_as_background;
    }


    /**
     * @param int $show_image_as_background
     */
    public function setShowImageAsBackground(int $show_image_as_background)/*: void*/
    {
        $this->show_image_as_background = $show_image_as_background;
    }


    /**
     * @return int
     */
    public function getShowPreconditions() : int
    {
        return $this->show_preconditions;
    }


    /**
     * @param int $show_preconditions
     */
    public function setShowPreconditions(int $show_preconditions)/*: void*/
    {
        $this->show_preconditions = $show_preconditions;
    }


    /**
     * @return int
     */
    public function getShowDownloadCertificate() : int
    {
        return $this->show_download_certificate;
    }


    /**
     * @param int $show_download_certificate
     */
    public function setShowDownloadCertificate(int $show_download_certificate)/*: void*/
    {
        $this->show_download_certificate = $show_download_certificate;
    }


    /**
     * @return int
     */
    public function getShowObjectTabs() : int
    {
        return $this->show_object_tabs;
    }


    /**
     * @param int $show_object_tabs
     */
    public function setShowObjectTabs(int $show_object_tabs)/*: void*/
    {
        $this->show_object_tabs = $show_object_tabs;
    }


    /**
     * @return int
     */
    public function getView() : int
    {
        return $this->view;
    }


    /**
     * @param int $view
     */
    public function setView(int $view)/*: void*/
    {
        $this->view = $view;
    }


    /**
     * @return int
     */
    public function getShadow() : int
    {
        return $this->shadow;
    }


    /**
     * @param int $shadow
     */
    public function setShadow(int $shadow)/*: void*/
    {
        $this->shadow = $shadow;
    }


    /**
     * @return int
     */
    public function getApplyColorsToGlobalSkin() : int
    {
        return $this->apply_colors_to_global_skin;
    }


    /**
     * @param int $apply_colors_to_global_skin
     */
    public function setApplyColorsToGlobalSkin(int $apply_colors_to_global_skin)/*: void*/
    {
        $this->apply_colors_to_global_skin = $apply_colors_to_global_skin;
    }


    /**
     * @return int
     */
    public function getShowLearningProgressFilter() : int
    {
        return $this->show_learning_progress_filter;
    }


    /**
     * @param int $show_learning_progress_filter
     */
    public function setShowLearningProgressFilter(int $show_learning_progress_filter)/*: void*/
    {
        $this->show_learning_progress_filter = $show_learning_progress_filter;
    }


    /**
     * @return int
     */
    public function getColumnsType() : int
    {
        return $this->columns_type;
    }


    /**
     * @param int $columns_type
     */
    public function setColumnsType(int $columns_type)/*: void*/
    {
        $this->columns_type = $columns_type;
    }


    /**
     * @return int
     */
    public function getColumns() : int
    {
        return $this->columns;
    }


    /**
     * @param int $columns
     */
    public function setColumns(int $columns)/*: void*/
    {
        $this->columns = $columns;
    }


    /**
     * @return int
     */
    public function getShowLanguageFlag() : int
    {
        return $this->show_language_flag;
    }


    /**
     * @param int $show_language_flag
     */
    public function setShowLanguageFlag(int $show_language_flag)/*: void*/
    {
        $this->show_language_flag = $show_language_flag;
    }


    /**
     * @return int
     */
    public function getLanguageFlagPosition() : int
    {
        return $this->language_flag_position;
    }


    /**
     * @param int $language_flag_position
     */
    public function setLanguageFlagPosition(int $language_flag_position)/*: void*/
    {
        $this->language_flag_position = $language_flag_position;
    }


    /**
     * @return string
     */
    public function _getBackgroundColor() : string
    {
        switch ($this->getBackgroundColorType()) {
            case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
                return $this->_getImageDominantColor();

            case Tile::COLOR_TYPE_SET:
                return $this->convertHexToRGB($this->getBackgroundColor());

            default:
                break;
        }

        return "";
    }


    /**
     * @return string
     */
    public function _getBorderColor() : string
    {
        switch ($this->getBorderColorType()) {
            case Tile::COLOR_TYPE_BACKGROUND:
                return $this->_getBackgroundColor();

            case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
                return $this->_getImageDominantColor();

            case Tile::COLOR_TYPE_SET:
                return $this->convertHexToRGB($this->getBorderColor());

            default:
                break;
        }

        return "";
    }


    /**
     * @return string
     */
    public function _getColumns() : string
    {
        switch ($this->getColumnsType()) {
            case Tile::SIZE_TYPE_COUNT:
                return "calc(100% / " . $this->getColumns() . ")";

            case Tile::SIZE_TYPE_PX:
                return $this->getColumns() . "px";

            default:
                break;
        }

        return "";
    }


    /**
     * @return string
     */
    public function _getFontColor() : string
    {
        switch ($this->getFontColorType()) {
            case Tile::COLOR_TYPE_CONTRAST:
                $background_color = $this->_getBackgroundColor();

                if (!empty($background_color)) {
                    return $this->getContrastYIQ($background_color);
                }
                break;

            case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
                return $this->_getImageDominantColor();

            case Tile::COLOR_TYPE_SET:
                return $this->convertHexToRGB($this->getFontColor());

            default:
                break;
        }

        return "";
    }


    /**
     * @return string
     */
    public function _getBorder() : string
    {
        $css = "";

        $border_color = $this->_getBorderColor();

        $border_size = $this->getBorderSize();

        if (!empty($border_color)) {
            $css .= "border-color:rgb(" . $border_color . ")!important;";
        }

        if (!empty($border_size)) {
            $css .= "border-width:" . $border_size . "px!important;";
        }

        return $css;
    }


    /**
     * @param bool $invert
     * @param bool $translucent
     *
     * @return string
     */
    public function _getColor(bool $invert = false, bool $translucent = false) : string
    {
        $css = "";

        $background_color = $this->_getBackgroundColor();

        $font_color = $this->_getFontColor();

        if ($invert) {
            if (!empty($font_color)) {
                if ($translucent) {
                    $font_color .= "," . self::SHOW_IMAGE_AS_BACKGROUND_COLOR_ALPHA;
                } else {
                    $font_color .= ",1";
                }
                $css .= "background-color:rgba(" . $font_color . ")!important;";
            }

            if (!empty($background_color)) {
                $css .= "color:rgb(" . $background_color . ")!important;";
            }
        } else {
            if (!empty($background_color)) {
                if ($translucent) {
                    $background_color .= "," . self::SHOW_IMAGE_AS_BACKGROUND_COLOR_ALPHA;
                } else {
                    $background_color .= ",1";
                }
                $css .= "background-color:rgba(" . $background_color . ")!important;";
            }

            if (!empty($font_color)) {
                $css .= "color:rgb(" . $font_color . ")!important;";
            }
        }

        return $css;
    }


    /**
     * @param bool $append_filename
     *
     * @return string
     */
    public function getImagePathAsRelative(bool $append_filename = true) : string
    {
        $path = ilSrTilePlugin::WEB_DATA_FOLDER . "/" . static::IMAGE_PREFIX . $this->getTileId() . "/";

        if ($append_filename) {
            $path .= $this->getImage();
        }

        return $path;
    }


    /**
     * @return string
     */
    public function getImagePath() : string
    {
        return ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->getImagePathAsRelative();
    }


    /**
     * @return string
     */
    public function getImagePathWithCheck() : string
    {
        if (!empty($this->getImage())) {
            if (file_exists($image_path = $this->getImagePath())) {
                return $image_path;
            }
        }

        return "";
    }


    /**
     * @param string $path_of_new_image
     */
    public function applyNewImage(string $path_of_new_image)/*: void*/
    {
        if (!empty($this->getImage())) {
            if (file_exists($image_old_path = $this->getImagePath())) {
                unlink($image_old_path);
            }
            $this->setImage("");

            self::colorThiefCaches()->delete($image_old_path);
        }

        if (!empty($path_of_new_image)) {
            if (file_exists($path_of_new_image)) {
                $this->setImage($this->getTileId() . "." . pathinfo($path_of_new_image, PATHINFO_EXTENSION));

                self::dic()->filesystem()->web()->createDir($this->getImagePathAsRelative(false));

                copy($path_of_new_image, $this->getImagePath());
            }
        }
    }


    /**
     * @return ilObject|null
     */
    public function _getIlObject()/*: ?ilObject*/
    {
        if ($this->il_object === null) {
            $this->il_object = ilObjectFactory::getInstanceByRefId($this->getObjRefId(), false);

            if ($this->il_object === false) {
                $this->il_object = null;
            }
        }

        return $this->il_object;
    }


    /**
     * @return string
     */
    public function _getLayout() : string
    {
        $layout = "";

        $margin = $this->getMargin();

        $columns = $this->_getColumns();

        if (!empty($margin)) {
            $layout .= "padding:" . $margin . "px!important;";
        }

        if ($this->getView() === self::VIEW_TILE && !empty($columns)) {
            $layout .= "width:" . $columns . "!important;";
        }

        return $layout;
    }


    /**
     * @return string
     */
    public function _getLink() : string
    {
        return ilLink::_getStaticLink($this->getObjRefId());
    }


    /**
     * @return string
     */
    public function _getOnClickLink() : string
    {
        $this->_getIlObject();

        $obj_ref_id = $this->getObjRefId();
        $type = $this->il_object->getType();
        $tile = $this;

        //write access - open normally!
        if (self::access()->hasWriteAccess($obj_ref_id)) {
            return ' href="' . htmlspecialchars($tile->_getLink()) . '""';
        }

        //open directly the one object if it's only one AND as READ ACCESS
        if ($this->getOpenObjWithOneChildDirect() === Tile::OPEN_TRUE && self::access()->hasReadAccess($obj_ref_id)) {

            switch (true) {
                case ($type === "crs"):
                case ($type === "cat"):
                case ($type === "grp"):
                case ($type === "fold"):
                case ($this instanceof TileReference):
                    if (count(self::dic()->tree()->getChilds($obj_ref_id)) === 1) {
                        $child_refs = self::dic()->tree()->getChilds($obj_ref_id);
                        $obj_ref_id = $child_refs[0]['child'];
                        $type = self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($obj_ref_id));
                        $tile = self::tiles()->getInstanceForObjRefId($obj_ref_id);
                    }
                    break;
                case  ($type === "webr"):
                    if (intval(ilLinkResourceItems::lookupNumberOfLinks($this->il_object->getId())) === 1) {
                        $link_arr = ilLinkResourceItems::_getFirstLink($this->il_object->getId());

                        return ' href="' . htmlspecialchars($link_arr['target']) . '""';
                    }
                    break;
            }
        }

        switch (true) {
            case  ($type === "sahs"):
                $slm_gui = new ilObjSCORMLearningModuleGUI("", $obj_ref_id, true, false);

                $sahs_obj = new ilObjSAHSLearningModule($obj_ref_id);
                $om = $sahs_obj->getOpenMode();
                $width = $sahs_obj->getWidth();
                $height = $sahs_obj->getHeight();

                if (($om == 5 || $om == 1) && $width > 0 && $height > 0) {
                    $om++;
                }

                self::dic()->ctrl()->setParameterByClass(ilSAHSPresentationGUI::class, Tiles::GET_PARAM_REF_ID, $obj_ref_id);

                return ' onclick="startSAHS(\'' . self::dic()->ctrl()->getLinkTargetByClass(ilSAHSPresentationGUI::class, '') . "','ilContObj"
                    . $slm_gui->object->getId() . "'," . $om . "," . $width . "," . $height . ');"';

            default:
                return ' href="' . htmlspecialchars($tile->_getLink()) . '""';
        }
    }


    /**
     * @return string
     */
    public function _getSize() : string
    {
        $size = "";

        $font_size = $this->getFontSize();

        if (!empty($font_size)) {
            $size .= "font-size:" . $font_size . "px!important;";
        }

        return $size;
    }


    /**
     * @return string
     */
    public function _getTitle() : string
    {
        if ($this->_getIlObject() !== null) {
            return $this->_getIlObject()->getTitle();
        }

        return "";
    }


    /**
     * @return string
     */
    public function _getImageDominantColor() : string
    {
        $image = $this->getImagePathWithCheck();

        $colorThiefCache = self::colorThiefCaches()->getColorThiefCache($image);

        if (!empty($image)) {
            if (empty($colorThiefCache->getColor())) {
                $dominantColor = ColorThief::getColor($image);

                if (is_array($dominantColor)) {
                    $colorThiefCache->setColor(implode(",", $dominantColor));
                }

                $colorThiefCache->store();
            }
        } else {
            $colorThiefCache->setColor("");
            $colorThiefCache->store();
        }

        return $colorThiefCache->getColor();
    }


    /**
     * https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
     *
     * @param string $hex_color
     *
     * @return string
     */
    private function convertHexToRGB(string $hex_color) : string
    {
        $hex_color = str_replace('#', '', $hex_color);

        if (!empty($hex_color)) {
            $length = strlen($hex_color);

            $rgb['r'] = hexdec($length == 6 ? substr($hex_color, 0, 2) : ($length == 3 ? str_repeat(substr($hex_color, 0, 1), 2) : 0));
            $rgb['g'] = hexdec($length == 6 ? substr($hex_color, 2, 2) : ($length == 3 ? str_repeat(substr($hex_color, 1, 1), 2) : 0));
            $rgb['b'] = hexdec($length == 6 ? substr($hex_color, 4, 2) : ($length == 3 ? str_repeat(substr($hex_color, 2, 1), 2) : 0));

            return implode(",", $rgb);
        } else {
            return "";
        }
    }


    /**
     * https://24ways.org/2010/calculating-color-contrast/
     *
     * @param string $rgb_color
     *
     * @return string
     */
    private function getContrastYIQ(string $rgb_color) : string
    {
        list($r, $g, $b) = explode(",", $rgb_color);

        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($yiq >= 128) ? self::COLOR_BLACK : self::COLOR_WHITE;
    }
}
