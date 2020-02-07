<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use ilAdvancedSelectionListGUI;
use ILIAS\UI\Component\Legacy\Legacy;
use ilObject;
use ilObjRootFolderGUI;
use ilRepositoryGUI;
use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use ilUIPluginRouterGUI;
use srag\CustomInputGUIs\SrTile\CustomInputGUIsTrait;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Certificate\CertificateGUI;
use srag\Plugins\SrTile\Config\ConfigFormGUI;
use srag\Plugins\SrTile\Favorite\FavoritesGUI;
use srag\Plugins\SrTile\ObjectLink\ObjectLink;
use srag\Plugins\SrTile\ObjectLink\ObjectLinksGUI;
use srag\Plugins\SrTile\OnlineStatus\OnlineStatusGUI;
use srag\Plugins\SrTile\Rating\RatingGUI;
use srag\Plugins\SrTile\Recommend\RecommendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Tile\TileGUI;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class AbstractSingleGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Martin Studer <ms@studer-raimann.ch>
 */
abstract class AbstractSingleGUI implements SingleGUIInterface
{

    use DICTrait;
    use SrTileTrait;
    use CustomInputGUIsTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var Tile
     */
    protected $tile;


    /**
     * AbstractSingleGUI constructor
     *
     * @param Tile $tile
     */
    public function __construct(Tile $tile)
    {
        $this->tile = $tile;
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        if (self::srTile()->tiles()->getInstanceForObjRefId(ilSrTileUIHookGUI::filterRefId() ?? ROOT_FOLDER_ID)->getShowLearningProgressFilter()
            === Tile::SHOW_TRUE
        ) {

            $lp_filters = self::srTile()->learningProgressFilters(self::dic()->user())->getFilter(intval(ilSrTileUIHookGUI::filterRefId()));

            if (count($lp_filters) > 0) {
                if (!in_array(self::srTile()->ilias()->learningProgress(self::dic()->user())->getStatus($this->tile->getObjRefId()), $lp_filters)) {
                    return "";
                }
            }
        }

        self::dic()->ctrl()->setParameterByClass(FavoritesGUI::class, FavoritesGUI::GET_PARAM_PARENT_REF_ID, ilSrTileUIHookGUI::filterRefId());
        self::dic()->ctrl()->setParameterByClass(FavoritesGUI::class, FavoritesGUI::GET_PARAM_REF_ID, $this->tile->getObjRefId());
        self::dic()->ctrl()->setParameterByClass(OnlineStatusGUI::class, FavoritesGUI::GET_PARAM_PARENT_REF_ID, ilSrTileUIHookGUI::filterRefId());
        self::dic()->ctrl()->setParameterByClass(OnlineStatusGUI::class, FavoritesGUI::GET_PARAM_REF_ID, $this->tile->getObjRefId());
        self::dic()->ctrl()->setParameterByClass(RatingGUI::class, RatingGUI::GET_PARAM_PARENT_REF_ID, ilSrTileUIHookGUI::filterRefId());
        self::dic()->ctrl()->setParameterByClass(RatingGUI::class, RatingGUI::GET_PARAM_REF_ID, $this->tile->getObjRefId());
        self::dic()->ctrl()->setParameterByClass(RecommendGUI::class, RecommendGUI::GET_PARAM_REF_ID, $this->tile->getObjRefId());

        $tpl = self::plugin()->template("TileSingle/single.html");
        $tpl->setCurrentBlock("tile");

        $tpl->setVariableEscaped("TILE_ID", $this->tile->getTileId());

        $tpl->setVariableEscaped("OBJECT_TYPE", ($this->tile->_getIlObject() !== null ? $this->tile->_getIlObject()->getType() : ""));

        if ($this->tile->getShowTitle() === Tile::SHOW_TRUE) {
            $tpl->setVariableEscaped("TITLE", $this->tile->_getTitle());
        }
        $tpl->setVariableEscaped("TITLE_HORIZONTAL_ALIGN", $this->tile->getLabelHorizontalAlign());
        $tpl->setVariableEscaped("TITLE_VERTICAL_ALIGN", $this->tile->getLabelVerticalAlign());

        $object_links = self::srTile()->objectLinks()->getShouldShowObjectLinks($this->tile->getObjRefId());

        if (!empty($object_links)) {
            $items = array_map(function (ObjectLink $object_link) : Legacy {
                $tpl_object_link = self::plugin()->template("ObjectLink/object_link.html");

                if ($this->tile->getShowLanguageFlag() === Tile::SHOW_TRUE) {
                    $tpl_object_link->setVariable("LANGUAGE_FLAG", self::srTile()->ilias()->metadata($object_link->getObject())->getLanguageImage());
                }

                $tpl_object_link->setVariableEscaped("TITLE", $object_link->getObject()->getTitle());

                $tpl_object_link->setVariable("LINK", self::srTile()->tiles()->getInstanceForObjRefId($object_link->getObjRefId())->_getAdvancedLink());

                return self::dic()->ui()->factory()->legacy(self::output()->getHTML($tpl_object_link));
            }, $object_links);

            if (self::srTile()->config()->getValue(ConfigFormGUI::KEY_ENABLED_OBJECT_LINKS_ONCE_SELECT)) {
                if (!self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId())) {
                    $message = self::plugin()->translate("can_not_be_changed_anymore", ObjectLinksGUI::LANG_MODULE);
                    if (self::version()->is54()) {
                        $message = self::dic()->ui()->factory()->messageBox()->info($message);
                    } else {
                        $message = self::dic()->ui()->factory()->legacy(self::dic()->ui()->mainTemplate()->getMessageHTML($message, "info"));
                    }

                    array_unshift($items, $message);
                }
            }

            $tpl->setVariable("OBJECT_LINKS",
                self::output()->getHTML(self::dic()->ui()->factory()->dropdown()->standard($items)->withLabel(self::plugin()->translate("open", ObjectLinksGUI::LANG_MODULE))));
        } else {
            $tpl->setVariable("LINK", $this->tile->_getAdvancedLink());
        }

        if (self::srTile()->access()->hasOpenAccess($this->tile)) {

            if ($this->tile->getShowOnlineStatusIcon() === Tile::SHOW_TRUE) {

                if (self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId()) && self::srTile()->onlineStatus()->supportsWriteOnline($this->tile->getObjRefId())) {

                    $tpl_online_status = self::plugin()->template("OnlineStatus/online_status.html");

                    if (self::srTile()->onlineStatus()->isOnline($this->tile->getObjRefId())) {
                        $tpl_online_status->setVariable("ONLINE_STATUS_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                            ilUIPluginRouterGUI::class,
                            OnlineStatusGUI::class
                        ], OnlineStatusGUI::CMD_SET_OFFLINE));
                        $tpl_online_status->setVariableEscaped("ONLINE_STATUS_TEXT", self::plugin()
                            ->translate("set_offline", OnlineStatusGUI::LANG_MODULE));
                        $tpl_online_status->setVariableEscaped("ONLINE_STATUS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/online.svg");
                    } else {
                        $tpl_online_status->setVariable("ONLINE_STATUS_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                            ilUIPluginRouterGUI::class,
                            OnlineStatusGUI::class
                        ], OnlineStatusGUI::CMD_SET_ONLINE));
                        $tpl_online_status->setVariableEscaped("ONLINE_STATUS_TEXT", self::plugin()
                            ->translate("set_online", OnlineStatusGUI::LANG_MODULE));
                        $tpl_online_status->setVariableEscaped("ONLINE_STATUS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/offline.svg");
                    }

                    $tpl->setVariable("ONLINE_STATUS", self::output()->getHTML($tpl_online_status));
                } else {
                    if (self::srTile()->onlineStatus()->supportsReadOnline($this->tile->getObjRefId())
                        && (!self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId()) ? !self::srTile()
                            ->onlineStatus()
                            ->isOnline($this->tile->getObjRefId()) : true)
                    ) {

                        $tpl_online_status = self::plugin()->template("OnlineStatus/online_status_readonly.html");

                        if (self::srTile()->onlineStatus()->isOnline($this->tile->getObjRefId())) {
                            $tpl_online_status->setVariableEscaped("ONLINE_STATUS_TEXT", self::plugin()
                                ->translate("online", OnlineStatusGUI::LANG_MODULE));
                            $tpl_online_status->setVariableEscaped("ONLINE_STATUS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/online.svg");
                        } else {
                            $tpl_online_status->setVariableEscaped("ONLINE_STATUS_TEXT", self::plugin()
                                ->translate("offline", OnlineStatusGUI::LANG_MODULE));
                            $tpl_online_status->setVariableEscaped("ONLINE_STATUS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/offline.svg");
                        }

                        $tpl->setVariable("ONLINE_STATUS", self::output()->getHTML($tpl_online_status));
                    }
                }
            }

            if (self::srTile()->favorites(self::dic()->user())->enabled()
                && $this->tile->getShowFavoritesIcon() === Tile::SHOW_TRUE
            ) {
                $tpl_favorite = self::plugin()->template("Favorite/favorite.html");

                if (self::srTile()->favorites(self::dic()->user())->hasFavorite($this->tile->getObjRefId())) {
                    $tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        FavoritesGUI::class
                    ], FavoritesGUI::CMD_REMOVE_FROM_FAVORITES));
                    $tpl_favorite->setVariableEscaped("FAVORITE_TEXT", self::plugin()
                        ->translate("remove_from_favorites", FavoritesGUI::LANG_MODULE));
                    $tpl_favorite->setVariableEscaped("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/favorite.svg");
                } else {
                    $tpl_favorite->setVariable("FAVORITE_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        FavoritesGUI::class
                    ], FavoritesGUI::CMD_ADD_TO_FAVORITES));
                    $tpl_favorite->setVariableEscaped("FAVORITE_TEXT", self::plugin()->translate("add_to_favorites", FavoritesGUI::LANG_MODULE));
                    $tpl_favorite->setVariableEscaped("FAVORITE_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unfavorite.svg");
                }

                $tpl->setVariable("FAVORITE", self::output()->getHTML($tpl_favorite));
            }

            if ($this->tile->getEnableRating() === Tile::SHOW_TRUE
                && self::srTile()->access()->hasReadAccess($this->tile->getObjRefId())
            ) {
                $tpl_rating = self::plugin()->template("Rating/rating.html");

                if (self::srTile()->ratings(self::dic()->user())->hasLike($this->tile->getObjRefId())) {
                    $tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        RatingGUI::class
                    ], RatingGUI::CMD_UNLIKE));
                    $tpl_rating->setVariableEscaped("RATING_TEXT", self::plugin()->translate("unlike", RatingGUI::LANG_MODULE));
                    $tpl_rating->setVariableEscaped("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/like.svg");
                } else {
                    $tpl_rating->setVariable("RATING_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        RatingGUI::class
                    ], RatingGUI::CMD_LIKE));
                    $tpl_rating->setVariableEscaped("RATING_TEXT", self::plugin()->translate("like", RatingGUI::LANG_MODULE));
                    $tpl_rating->setVariableEscaped("RATING_IMAGE_PATH", self::plugin()->directory() . "/templates/images/unlike.svg");
                }

                if ($this->tile->getShowLikesCount() === Tile::SHOW_TRUE) {
                    $likes_count = self::srTile()->ratings(self::dic()->user())->getLikesCount($this->tile->getObjRefId());

                    if ($likes_count > 0) {
                        $tpl_likes_count = self::plugin()->template("Rating/likes_count.html");
                        $tpl_likes_count->setVariableEscaped("LIKES_COUNT", $likes_count);
                        $tpl_rating->setVariable("LIKES_COUNT", self::output()->getHTML($tpl_likes_count));
                    }
                }

                $tpl->setVariable("RATING", self::output()->getHTML($tpl_rating));
            }

            if ($this->tile->getShowRecommendIcon() === Tile::SHOW_TRUE
                && !empty($this->tile->getRecommendMailTemplate())
                && self::srTile()->access()->hasReadAccess($this->tile->getObjRefId())
            ) {
                $tpl_recommend = self::plugin()->template("Recommend/recommend.html");

                $tpl_recommend->setVariable("RECOMMEND_LINK", self::dic()->ctrl()->getLinkTargetByClass([
                    ilUIPluginRouterGUI::class,
                    RecommendGUI::class
                ], RecommendGUI::CMD_ADD_RECOMMEND, "", true));
                $tpl_recommend->setVariableEscaped("RECOMMEND_TEXT", self::plugin()->translate("recommend", RecommendGUI::LANG_MODULE));
                $tpl_recommend->setVariableEscaped("RECOMMEND_IMAGE_PATH", self::plugin()->directory() . "/templates/images/recommend.svg");

                $tpl->setVariable("RECOMMEND", self::output()->getHTML($tpl_recommend));
            }

            if (self::srTile()->ilias()->learningProgress(self::dic()->user())->hasLearningProgress($this->tile->getObjRefId())) {
                switch ($this->tile->getShowLearningProgress()) {
                    case Tile::LEARNING_PROGRESS_ICON:
                        $icon = self::srTile()->ilias()->learningProgress(self::dic()->user())->getIcon($this->tile->getObjRefId());

                        $tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

                        $tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::dic()->ui()->factory()->image()
                            ->standard($icon, "")));

                        $tpl_learning_progress->setVariableEscaped("LEARNING_PROGRESS_POSITION", $this->tile->getLearningProgressPosition());

                        $tpl_learning_progress->setVariableEscaped("LEARNING_PROGRESS_TEXT", self::srTile()->ilias()->learningProgress(self::dic()->user())
                            ->getText($this->tile->getObjRefId()));

                        if ($this->tile->getLearningProgressPosition() === Tile::POSITION_ON_THE_ICONS) {
                            $tpl->setVariable("LEARNING_PROGRESS_ON_THE_ICONS", self::output()->getHTML($tpl_learning_progress));
                        } else {
                            $tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
                        }
                        break;

                    case Tile::LEARNING_PROGRESS_METER:
                        $learning_progress_bar = self::srTile()->ilias()->learningProgressBar(self::dic()->user(), $this->tile->getObjRefId());

                        $tpl_learning_progress = self::plugin()->template("LearningProgress/learning_progress.html");

                        $tpl_learning_progress->setVariable("LEARNING_PROGRESS", self::output()->getHTML(self::customInputGUIs()->progressMeter()
                            ->mini($learning_progress_bar->getTotalObjects(), $learning_progress_bar->getCompletedObjects())));

                        $tpl_learning_progress->setVariableEscaped("LEARNING_PROGRESS_POSITION", $this->tile->getLearningProgressPosition());

                        $tpl_learning_progress->setVariableEscaped("LEARNING_PROGRESS_TEXT", self::srTile()->ilias()->learningProgress(self::dic()->user())
                            ->getText($this->tile->getObjRefId()));

                        if ($this->tile->getLearningProgressPosition() === Tile::POSITION_ON_THE_ICONS) {
                            $tpl->setVariable("LEARNING_PROGRESS_ON_THE_ICONS", self::output()->getHTML($tpl_learning_progress));
                        } else {
                            $tpl->setVariable("LEARNING_PROGRESS", self::output()->getHTML($tpl_learning_progress));
                        }
                        break;

                    default:
                        break;
                }
            }

            if ($this->tile->getShowLanguageFlag() === Tile::SHOW_TRUE) {
                $tpl_language_flag = self::plugin()->template("LanguageFlag/language_flag.html");

                $tpl_language_flag->setVariable("LANGUAGE_FLAG", self::srTile()->ilias()->metadata($this->tile->_getIlObject())->getLanguageImage());

                $tpl_language_flag->setVariableEscaped("LANGUAGE_FLAG_POSITION", $this->tile->getLanguageFlagPosition());

                $tpl->setVariable("LANGUAGE_FLAG", self::output()->getHTML($tpl_language_flag));
            }
        }

        $image = $this->tile->getImagePathWithCheck();
        $tpl_image = self::plugin()->template("TileSingle/image.html");
        $tpl_image->setVariableEscaped("IMAGE", (!empty($image) ? "./" . $image : ""));
        $tpl->setVariable("IMAGE", self::output()->getHTML($tpl_image));

        $tpl->setVariableEscaped("IMAGE_POSITION", $this->tile->getImagePosition());
        $tpl->setVariableEscaped("IMAGE_SHOW_AS_BACKGROUND", $this->tile->getShowImageAsBackground());

        switch ($this->tile->getShowActions()) {
            case Tile::SHOW_ACTIONS_ALWAYS:
                $tpl->setVariable("ACTIONS", $this->getActions($object_links));
                break;

            case Tile::SHOW_ACTIONS_ONLY_WITH_WRITE_PERMISSIONS:
                if (self::srTile()->access()->hasWriteAccess($this->tile->getObjRefId())) {
                    $tpl->setVariable("ACTIONS", $this->getActions($object_links));
                }
                break;

            case Tile::SHOW_ACTIONS_NONE:
            default:
                break;
        }
        $tpl->setVariableEscaped("ACTIONS_POSITION", $this->tile->getActionsPosition());
        $tpl->setVariableEscaped("ACTIONS_VERTICAL_ALIGN", $this->tile->getActionsVerticalAlign());

        if ($this->tile->getObjectIconPosition() !== Tile::POSITION_NONE) {
            $icon = ilObject::_getIcon(($this->tile->_getIlObject() !== null ? $this->tile->_getIlObject()->getId() : null), "small");
            if (file_exists($icon)) {
                $tpl_object_icon = self::plugin()->template("Object/object_icon.html");

                $tpl_object_icon->setVariable("OBJECT_ICON", self::output()->getHTML(self::dic()->ui()->factory()->image()->standard($icon, "")));

                $tpl_object_icon->setVariableEscaped("OBJECT_ICON_POSITION", $this->tile->getObjectIconPosition());

                $tpl->setVariable("OBJECT_ICON", self::output()->getHTML($tpl_object_icon));
            }
        }

        if ($this->tile->getShowPreconditions() === Tile::SHOW_TRUE) {
            if (count(self::srTile()->ilias()->courses()->getPreconditions($this->tile->getObjRefId())) > 0) {
                $tpl_preconditions = self::plugin()->template("Preconditions/preconditions.html");

                $tpl_preconditions->setVariableEscaped("PRECONDITIONS_TEXT", self::plugin()->translate("preconditions", TileGUI::LANG_MODULE));
                $tpl_preconditions->setVariableEscaped("PRECONDITIONS_IMAGE_PATH", self::plugin()->directory() . "/templates/images/preconditions.svg");

                self::dic()->ctrl()->setParameterByClass(TileGUI::class, TileGUI::GET_PARAM_REF_ID, $this->tile->getObjRefId());
                $popover = self::dic()->ui()->factory()->popover()->standard(self::dic()->ui()->factory()->legacy(""))
                    ->withAsyncContentUrl(str_replace("\\", "\\\\", self::dic()->ctrl()->getLinkTargetByClass([
                        ilUIPluginRouterGUI::class,
                        TileGUI::class
                    ], TileGUI::CMD_GET_PRECONDITIONS, "", true)));

                // Use a fake button to use clickable open popover. Set the button id on the info image
                $button = self::dic()->ui()->factory()->button()->standard("", "")->withOnClick($popover->getShowSignal());
                $button_html = self::output()->getHTML($button);
                $button_id = [];
                preg_match('/id="([a-z0-9_]+)"/', $button_html, $button_id);
                if (is_array($button_id) && count($button_id) > 1) {
                    $button_id = $button_id[1];
                    $tpl_preconditions->setVariableEscaped("BUTTON_ID", $button_id);
                }

                $tpl->setVariable("PRECONDITIONS", self::output()->getHTML([$tpl_preconditions, $popover]));
            }
        }

        if (self::srTile()->ilias()->certificates(self::dic()->user(), $this->tile)->enabled()
            && $this->tile->getShowDownloadCertificate() === Tile::SHOW_TRUE
        ) {
            $tpl->setVariable("CERTIFICATE", self::output()->getHTML(new CertificateGUI(self::dic()->user(), $this->tile)));
        }

        $tpl->setVariableEscaped("SHADOW", $this->tile->getShadow());

        $tpl->parseCurrentBlock();

        return self::output()->getHTML($tpl);
    }


    /**
     * @inheritDoc
     */
    public function getActions(array $object_links = []) : string
    {
        if (self::dic()->ctrl()->isAsynch()) {
            // Hide because not work for asynch asynch load (Preconditions) - Some missing javascript int call on asynch
            return "";
        }

        $advanced_selection_list = new ilAdvancedSelectionListGUI();
        $advanced_selection_list->setAsynch(true);
        $advanced_selection_list->setId("act_" . $this->tile->getObjRefId() . "_tile_" . $this->tile->getTileId());
        $advanced_selection_list->setAsynchUrl($this->getActionAsyncUrl());

        if (!empty($object_links)) {
            $advanced_selection_list->setListTitle(self::plugin()->translate("actions", TileGUI::LANG_MODULE));
        }

        return self::output()->getHTML($advanced_selection_list);
    }


    /**
     * @inheritDoc
     */
    public function getActionAsyncUrl() : string
    {
        self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, ilSrTileUIHookGUI::GET_PARAM_REF_ID, (ilSrTileUIHookGUI::filterRefId() ?: 1));
        self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", $this->tile->getObjRefId());

        if (!empty(ilSrTileUIHookGUI::filterRefId())) {
            self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, ilSrTileUIHookGUI::GET_RENDER_EDIT_TILE_ACTION, 1);
        }

        $async_url = self::dic()->ctrl()->getLinkTargetByClass([
            ilRepositoryGUI::class,
            ilObjRootFolderGUI::class
        ], "getAsynchItemList", "", true, false);

        self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, ilSrTileUIHookGUI::GET_PARAM_REF_ID, null);
        self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, "cmdrefid", null);
        self::dic()->ctrl()->setParameterByClass(ilObjRootFolderGUI::class, ilSrTileUIHookGUI::GET_RENDER_EDIT_TILE_ACTION, null);

        return $async_url;
    }
}
