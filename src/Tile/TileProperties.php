<?php

namespace srag\Plugins\SrTile\Tile;

use ilLink;
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
class TileProperties {

	use DICTrait;
	use SrTileTrait;
	const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
	const COLOR_BLACK = "000000";
	const COLOR_WHITE = "FFFFFF";
	/**
	 * @var Tile
	 */
	protected $tile;
	/**
	 * @var Tile|null
	 */
	protected $parent_tile;


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
		if ($this->tile->getBackgroundColorType() !== Tile::COLOR_TYPE_PARENT) {
			if (!empty($this->tile->getBackgroundColor())) {
				return $this->tile->getBackgroundColor();
			}
		} else {
			if ($this->parent_tile !== NULL) {
				return $this->parent_tile->getProperties()->getBackgroundColor();
			}
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

			case Tile::COLOR_TYPE_SET:
				return $this->tile->getBorderColor();

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

			case Tile::COLOR_TYPE_SET:
				return $this->tile->getFontColor();

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
	 * @return string
	 */
	public function getImage(): string {
		if (!empty($this->tile->getImage())) {
			$image_path = ILIAS_WEB_DIR . "/" . CLIENT_ID . "/" . $this->tile->returnRelativeImagePath(true);
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
	public function getShowLearningProcess(): int {
		if ($this->tile->getShowLearningProcess() !== Tile::LEARNING_PROCCESS_PARENT) {
			return $this->tile->getShowLearningProcess();
		}

		if ($this->parent_tile !== NULL) {
			return $this->parent_tile->getProperties()->getShowLearningProcess();
		}

		return Tile::DEFAULT_SHOW_LEARNING_PROCCESS;
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
			$css .= "border-color:#" . $border_color . "!important;";
		}

		if (!empty($border_size)) {
			$css .= "border-width:" . $border_size . "px!important;";
		}

		return $css;
	}


	/**
	 * @param bool $invert
	 * @param bool $border
	 *
	 * @return string
	 */
	public function getColor(bool $invert = false): string {
		$css = "";

		$background_color = $this->getBackgroundColor();

		$font_color = $this->getFontColor();

		if ($invert) {
			if (!empty($background_color)) {
				$css .= "color:#" . $background_color . "!important;";
			}

			if (!empty($font_color)) {
				$css .= "background-color:#" . $font_color . "!important;";
			}
		} else {
			if (!empty($background_color)) {
				$css .= "background-color:#" . $background_color . "!important;";
			}

			if (!empty($font_color)) {
				$css .= "color:#" . $font_color . "!important;";
			}
		}

		return $css;
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
	 * https://24ways.org/2010/calculating-color-contrast/
	 *
	 * @param string $hexcolor
	 *
	 * @return string
	 */
	private function getContrastYIQ(string $hexcolor): string {
		$r = hexdec(substr($hexcolor, 0, 2));
		$g = hexdec(substr($hexcolor, 2, 2));
		$b = hexdec(substr($hexcolor, 4, 2));

		$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		return ($yiq >= 128) ? self::COLOR_BLACK : self::COLOR_WHITE;
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
	public function getTitle(): string {
		if ($this->tile->getIlObject() !== NULL) {
			return $this->tile->getIlObject()->getTitle();
		}

		return "";
	}
}
