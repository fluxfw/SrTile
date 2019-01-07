<?php

namespace srag\Plugins\SrTile\Tile;

use ColorThief\ColorThief;
use ilLink;
use ilObject;
use ilObjectFactory;
use ilObjSAHSLearningModule;
use ilObjSCORMLearningModuleGUI;
use ilSAHSPresentationGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class TileProperties
 *
 * @package srag\Plugins\SrTile\Tile
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class TileProperties {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const COLOR_BLACK = "0,0,0";
	const COLOR_WHITE = "255,255,255";
	const SHOW_IMAGE_AS_BACKGROUND_COLOR_ALPHA = 0.6;
	/**
	 * @var Tile
	 */
	protected $tile;
	/**
	 * @var Tile|null
	 */
	protected $parent_tile;
	/**
	 * @var ilObject|null
	 */
	protected $il_object = NULL;
	/**
	 * @var string[]
	 */
	protected static $image_dominant_color_cache = [];


	/**
	 * Tile constructor
	 *
	 * @param Tile $tile
	 */
	public function __construct(Tile $tile) {
		$this->tile = $tile;
		$this->parent_tile = self::tiles()->getParentTile($this->tile);
	}


	/**
	 * @return int
	 */
	public function getActionsPosition(): int {
		if ($this->tile->getActionsPosition() !== Tile::POSITION_PARENT) {
			return $this->tile->getActionsPosition();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getActionsPosition();
		}

		return Tile::DEFAULT_ACTIONS_POSITION;
	}


	/**
	 * @return int
	 */
	public function getActionsVerticalAlign(): int {
		if ($this->tile->getActionsVerticalAlign() !== Tile::VERTICAL_ALIGN_PARENT) {
			return $this->tile->getActionsVerticalAlign();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getActionsVerticalAlign();
		}

		return Tile::DEFAULT_ACTIONS_VERTICAL_ALIGN;
	}


	/**
	 * @return string
	 */
	public function getBackgroundColor(): string {
		switch ($this->tile->getBackgroundColorType()) {
			case Tile::COLOR_TYPE_PARENT:
				if ($this->parent_tile !== NULL) {
					return $this->parent_tile->getProperties()->getBackgroundColor();
				}
				break;

			case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
				return $this->getImageDominantColor();

			case Tile::COLOR_TYPE_SET:
				return $this->convertHexToRGB($this->tile->getBackgroundColor());

			default:
				break;
		}

		return "";
	}


	/**
	 * @return string
	 */
	public function getBorderColor(): string {
		switch ($this->tile->getBorderColorType()) {
			case Tile::COLOR_TYPE_PARENT:
				if ($this->parent_tile !== NULL) {
					return $this->parent_tile->getProperties()->getBorderColor();
				}
				break;

			case Tile::COLOR_TYPE_BACKGROUND:
				return $this->getBackgroundColor();

			case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
				return $this->getImageDominantColor();

			case Tile::COLOR_TYPE_SET:
				return $this->convertHexToRGB($this->tile->getBorderColor());

			default:
				break;
		}

		return "";
	}


	/**
	 * @return int
	 */
	public function getBorderSize(): int {
		if ($this->tile->getBorderSizeType() !== Tile::SIZE_TYPE_PARENT) {
			return $this->tile->getBorderSize();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getBorderSize();
		}

		return Tile::DEFAULT_BORDER_SIZE;
	}


	/**
	 * @return int
	 */
	public function getEnableRating(): int {
		if ($this->tile->getEnableRating() !== Tile::SHOW_PARENT) {
			return $this->tile->getEnableRating();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getEnableRating();
		}

		return Tile::DEFAULT_ENABLE_RATING;
	}


	/**
	 * @return string
	 */
	public function getFontColor(): string {
		switch ($this->tile->getFontColorType()) {
			case Tile::COLOR_TYPE_PARENT:
				if ($this->parent_tile !== NULL) {
					return $this->parent_tile->getProperties()->getFontColor();
				}
				break;

			case Tile::COLOR_TYPE_CONTRAST:
				$background_color = $this->getBackgroundColor();

				if (!empty($background_color)) {
					return $this->getContrastYIQ($background_color);
				}
				break;

			case Tile::COLOR_TYPE_AUTO_FROM_IMAGE:
				return $this->getImageDominantColor();

			case Tile::COLOR_TYPE_SET:
				return $this->convertHexToRGB($this->tile->getFontColor());

			default:
				break;
		}

		return "";
	}


	/**
	 * @return int
	 */
	public function getFontSize(): int {
		if ($this->tile->getFontSizeType() !== Tile::SIZE_TYPE_PARENT) {
			if (!empty($this->tile->getFontSize())) {
				return $this->tile->getFontSize();
			}
		} else {
			if ($this->parent_tile !== NULL) {
				return $this->parent_tile->getProperties()->getFontSize();
			}
		}

		return Tile::DEFAULT_FONT_SIZE;
	}


	/**
	 * @return int
	 */
	public function getImagePosition(): int {
		if ($this->tile->getImagePosition() !== Tile::POSITION_PARENT) {
			return $this->tile->getImagePosition();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getImagePosition();
		}

		return Tile::DEFAULT_IMAGE_POSITION;
	}


	/**
	 * @return int
	 */
	public function getLabelHorizontalAlign(): int {
		if ($this->tile->getLabelHorizontalAlign() !== Tile::HORIZONTAL_ALIGN_PARENT) {
			return $this->tile->getLabelHorizontalAlign();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getLabelHorizontalAlign();
		}

		return Tile::DEFAULT_LABEL_HORIZONTAL_ALIGN;
	}


	/**
	 * @return int
	 */
	public function getLabelVerticalAlign(): int {
		if ($this->tile->getLabelVerticalAlign() !== Tile::VERTICAL_ALIGN_PARENT) {
			return $this->tile->getLabelVerticalAlign();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getLabelVerticalAlign();
		}

		return Tile::DEFAULT_LABEL_VERTICAL_ALIGN;
	}


	/**
	 * @return int
	 */
	public function getLearningProgressPosition(): int {
		if ($this->tile->getLearningProgressPosition() !== Tile::POSITION_PARENT) {
			return $this->tile->getLearningProgressPosition();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getLearningProgressPosition();
		}

		return Tile::DEFAULT_LEARNING_PROGRESS_POSITION;
	}


	/**
	 * @return int
	 */
	public function getMargin(): int {
		if ($this->tile->getMarginType() !== Tile::SIZE_TYPE_PARENT) {
			if (!empty($this->tile->getMargin())) {
				return $this->tile->getMargin();
			}
		} else {
			if ($this->parent_tile !== NULL) {
				return $this->parent_tile->getProperties()->getMargin();
			}
		}

		return Tile::DEFAULT_MARGIN;
	}


	/**
	 * @return int
	 */
	public function getObjectIconPosition(): int {
		if ($this->tile->getObjectIconPosition() !== Tile::POSITION_PARENT) {
			return $this->tile->getObjectIconPosition();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getObjectIconPosition();
		}

		return Tile::DEFAULT_OBJECT_ICON_POSITION;
	}


	/**
	 * @return int
	 */
	public function getOpenObjWithOneChildDirect(): int {
		if ($this->tile->getOpenObjWithOneChildDirect() !== Tile::OPEN_PARENT) {
			return $this->tile->getOpenObjWithOneChildDirect();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getOpenObjWithOneChildDirect();
		}

		return Tile::DEFAULT_OPEN_OBJ_WITH_ONE_CHILD_DIRECT;
	}


	/**
	 * @return string
	 */
	public function getRecommendMailTemplate(): string {
		if ($this->tile->getRecommendMailTemplateType() !== Tile::MAIL_TEMPLATE_PARENT) {
			if (!empty($this->tile->getRecommendMailTemplate())) {
				return $this->tile->getRecommendMailTemplate();
			}
		} else {
			if ($this->parent_tile !== NULL) {
				return $this->parent_tile->getProperties()->getRecommendMailTemplate();
			}
		}

		return "";
	}


	/**
	 * @return int
	 */
	public function getShowActions(): int {
		if ($this->tile->getShowActions() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowActions();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowActions();
		}

		return Tile::DEFAULT_SHOW_ACTIONS;
	}


	/**
	 * @return int
	 */
	public function getShowFavoritesIcon(): int {
		if ($this->tile->getShowFavoritesIcon() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowFavoritesIcon();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowFavoritesIcon();
		}

		return Tile::DEFAULT_SHOW_FAVORITES_ICON;
	}


	/**
	 * @return int
	 */
	public function getShowImageAsBackground(): int {
		if ($this->tile->getShowImageAsBackground() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowImageAsBackground();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowImageAsBackground();
		}

		return Tile::DEFAULT_SHOW_IMAGE_AS_BACKGROUND;
	}


	/**
	 * @return int
	 */
	public function getShowLearningProgress(): int {
		if ($this->tile->getShowLearningProgress() !== Tile::LEARNING_PROGRESS_PARENT) {
			return $this->tile->getShowLearningProgress();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowLearningProgress();
		}

		return Tile::DEFAULT_SHOW_LEARNING_PROGRESS;
	}


	/**
	 * @return int
	 */
	public function getShowLearningProgressLegend(): int {
		if ($this->tile->getShowLearningProgressLegend() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowLearningProgressLegend();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowLearningProgressLegend();
		}

		return Tile::DEFAULT_SHOW_LEARNING_PROGRESS_LEGEND;
	}


	/**
	 * @return int
	 */
	public function getShowLikesCount(): int {
		if ($this->tile->getShowLikesCount() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowLikesCount();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowLikesCount();
		}

		return Tile::DEFAULT_SHOW_LIKES_COUNT;
	}


	/**
	 * @return int
	 */
	public function getShowRecommendIcon(): int {
		if ($this->tile->getShowRecommendIcon() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowRecommendIcon();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowRecommendIcon();
		}

		return Tile::DEFAULT_SHOW_RECOMMEND_ICON;
	}


	/**
	 * @return int
	 */
	public function getShowTitle(): int {
		if ($this->tile->getShowTitle() !== Tile::SHOW_PARENT) {
			return $this->tile->getShowTitle();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowTitle();
		}

		return Tile::DEFAULT_SHOW_TITLE;
	}


	/**
	 * @return string
	 */
	public function getBorder(): string {
		$css = "";

		$border_color = $this->getBorderColor();

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
	public function getColor(bool $invert = false, bool $translucent = false): string {
		$css = "";

		$background_color = $this->getBackgroundColor();

		$font_color = $this->getFontColor();

		if ($invert) {
			if (!empty($font_color)) {
				if ($translucent) {
					$font_color .= "," . self::SHOW_IMAGE_AS_BACKGROUND_COLOR_ALPHA;
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
	 * @return string
	 */
	public function getImage(): string {
		if (!empty($this->tile->getImage())) {
			$image_path = $this->getImageWebRootRelativePath();
			if (file_exists($image_path)) {
				return "./" . $image_path;
			}
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getImage();
		}

		return self::plugin()->directory() . "/templates/images/default_image.png";
	}


	/**
	 * @return string
	 */
	public function getImageDominantColor(): string {
		$image = $this->getImage();

		if (!isset(self::$image_dominant_color_cache[$image])) {

			if (file_exists($image)) {
				$dominantColor = ColorThief::getColor($image);

				if (is_array($dominantColor)) {
					self::$image_dominant_color_cache[$image] = implode(",", $dominantColor);
				} else {
					self::$image_dominant_color_cache[$image] = "";
				}
			} else {
				self::$image_dominant_color_cache[$image] = "";
			}
		}

		return self::$image_dominant_color_cache[$image];
	}


	/**
	 * @return string
	 */
	public function getImageWebRootRelativePath(): string {
		return ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->getImageRelativePath();
	}


	/**
	 * Return the path to the icon
	 *
	 * @param bool $append_filename If true, append filename of image
	 *
	 * @return string
	 */
	public function getImageRelativePath(bool $append_filename = true): string {
		$path = ilSrTilePlugin::WEB_DATA_FOLDER . "/" . "tile_" . $this->tile->getTileId() . "/";

		if ($append_filename) {
			$path .= $this->tile->getImage();
		}

		return $path;
	}


	/**
	 * @return ilObject|null
	 */
	public function getIlObject()/*: ?ilObject*/ {
		if ($this->il_object === NULL) {
			$this->il_object = ilObjectFactory::getInstanceByRefId($this->tile->getObjRefId(), false);

			if ($this->il_object === false) {
				$this->il_object = NULL;
			}
		}

		return $this->il_object;
	}


	/**
	 * @return string
	 */
	public function getLink(): string {
		return ilLink::_getStaticLink($this->tile->getObjRefId());
	}


	/**
	 * @return string
	 */
	public function getOnClickLink(): string {
		$ref_id = $this->il_object->getRefId();
		$type = $this->il_object->getType();
		$tile = $this->tile;

		//write access - open normally!
		if (self::access()->hasWriteAccess($ref_id)) {
			return ' href="' . htmlspecialchars($tile->getProperties()->getLink()) . '""';
		}

		//open directly the one object if it's only one
		if ($this->getOpenObjWithOneChildDirect() === Tile::OPEN_TRUE) {
			if (count(self::dic()->tree()->getChilds($ref_id)) === 1) {
				$child_refs = self::dic()->tree()->getChilds($ref_id);
				$ref_id = $child_refs[0]['child'];
				$type = self::dic()->objDataCache()->lookupType(self::dic()->objDataCache()->lookupObjId($ref_id));
				$tile = self::tiles()->getInstanceForObjRefId($ref_id);
			}
		}

		switch ($type) {
			case "sahs":
				$slm_gui = new ilObjSCORMLearningModuleGUI("", $ref_id, true, false);

				$sahs_obj = new ilObjSAHSLearningModule($ref_id);
				$om = $sahs_obj->getOpenMode();
				$width = $sahs_obj->getWidth();
				$height = $sahs_obj->getHeight();

				if (($om == 5 || $om == 1) && $width > 0 && $height > 0) {
					$om ++;
				}

				self::dic()->ctrl()->setParameterByClass(ilSAHSPresentationGUI::class, "ref_id", $ref_id);

				return ' onclick="startSAHS(\'' . self::dic()->ctrl()->getLinkTargetByClass(ilSAHSPresentationGUI::class, '') . "','ilContObj"
					. $slm_gui->object->getId() . "'," . $om . "," . $width . "," . $height . ');"';
				break;

			default:
				return ' href="' . htmlspecialchars($tile->getProperties()->getLink()) . '""';
		}
	}


	/**
	 * @return string
	 */
	public function getSize(): string {
		$size = "";

		$margin = $this->getMargin();

		$font_size = $this->getFontSize();

		if (!empty($margin)) {
			$size .= "margin:" . $margin . "px!important;";
		}

		if (!empty($font_size)) {
			$size .= "font-size:" . $font_size . "px!important;";
		}

		return $size;
	}


	/**
	 * @return string
	 */
	public function getTitle(): string {
		if ($this->getIlObject() !== NULL) {
			return $this->getIlObject()->getTitle();
		}

		return "";
	}


	/**
	 * https://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
	 *
	 * @param string $hex_color
	 *
	 * @return string
	 */
	private function convertHexToRGB(string $hex_color): string {
		$hex_color = str_replace('#', '', $hex_color);

		$length = strlen($hex_color);

		$rgb['r'] = hexdec($length == 6 ? substr($hex_color, 0, 2) : ($length == 3 ? str_repeat(substr($hex_color, 0, 1), 2) : 0));
		$rgb['g'] = hexdec($length == 6 ? substr($hex_color, 2, 2) : ($length == 3 ? str_repeat(substr($hex_color, 1, 1), 2) : 0));
		$rgb['b'] = hexdec($length == 6 ? substr($hex_color, 4, 2) : ($length == 3 ? str_repeat(substr($hex_color, 2, 1), 2) : 0));

		return implode(",", $rgb);
	}


	/**
	 * https://24ways.org/2010/calculating-color-contrast/
	 *
	 * @param string $rgb_color
	 *
	 * @return string
	 */
	private function getContrastYIQ(string $rgb_color): string {
		list($r, $g, $b) = explode(",", $rgb_color);

		$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		return ($yiq >= 128) ? self::COLOR_BLACK : self::COLOR_WHITE;
	}
}
