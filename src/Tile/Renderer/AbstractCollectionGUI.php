<?php

namespace srag\Plugins\SrTile\Tile\Renderer;

use ilSrTilePlugin;
use ilSrTileUIHookGUI;
use srag\DIC\SrTile\DICTrait;
use srag\DIC\SrTile\Version\PluginVersionParameter;
use srag\Plugins\SrTile\LearningProgress\LearningProgressFilterGUI;
use srag\Plugins\SrTile\LearningProgress\LearningProgressLegendGUI;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class AbstractCollectionGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer
 */
abstract class AbstractCollectionGUI implements CollectionGUIInterface
{

    use SrTileTrait;
    use DICTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var CollectionInterface $collection
     */
    protected $collection;


    /**
     * AbstractCollectionGUI constructor
     *
     * @param mixed $param
     */
    public function __construct($param)
    {
        $this->collection = self::srTile()->tiles()->renderer()->factory()->newCollectionInstance($this, $param);
    }


    /**
     * @inheritDoc
     */
    public function hideOriginalRowsOfTiles() /*: void*/
    {
        $css = '';

        $parent_tile = self::srTile()->tiles()->getInstanceForObjRefId(ilSrTileUIHookGUI::filterRefId() ?? ROOT_FOLDER_ID);

        $css .= '.tile';
        $css .= '{' . $parent_tile->_getLayout() . '}';

        $is_parent_css_rendered = false;
        foreach ($this->collection->getTiles() as $tile) {
            $css .= '#sr_tile_' . $tile->getTileId();
            $css .= '{' . $tile->_getSize() . '}';

            $css .= '#sr_tile_' . $tile->getTileId() . ' .card_bottom';
            $css .= '{' . $tile->_getColor(false, true) . '}';

            $css .= '#sr_tile_' . $tile->getTileId() . ' > .card';
            $css .= '{' . $tile->_getColor() . $tile->_getBorder() . '}';

            $css .= '#sr_tile_' . $tile->getTileId() . ' .btn-default, ';
            $css .= '#sr_tile_' . $tile->getTileId() . ' .badge';
            $css .= '{' . $tile->_getColor(true) . '}';

            if (!$is_parent_css_rendered) {
                $is_parent_css_rendered = true;

                if ($parent_tile->getApplyColorsToGlobalSkin() === Tile::SHOW_TRUE) {
                    if (!empty($parent_tile->_getBackgroundColor())) {
                        $css .= 'a#il_mhead_t_focus';
                        $css .= '{color:rgb(' . $parent_tile->_getBackgroundColor() . ')!important;}';
                    }

                    $css .= '.btn-default';
                    $css .= '{' . $tile->_getColor();
                    if (!empty($parent_tile->_getBackgroundColor())) {
                        $css .= 'border-color:rgb(' . $parent_tile->_getBackgroundColor() . ')!important;';
                    }
                    $css .= '}';
                }
            }
        }

        if (self::version()->is6()) {
            self::dic()->ui()->mainTemplate()->addCss("data:text/css;base64," . base64_encode($css));
        } else {
            self::dic()->ui()->mainTemplate()->addInlineCss($css);
        }
    }


    /**
     * @inheritDoc
     */
    public function render() : string
    {
        $this->initJS();

        $collection_html = "";

        if (count($this->collection->getTiles()) > 0) {

            $version_parameter = PluginVersionParameter::getInstance()->withPlugin(self::plugin());

            $parent_tile = self::srTile()->tiles()->getInstanceForObjRefId(ilSrTileUIHookGUI::filterRefId() ?? ROOT_FOLDER_ID);

            self::dic()->ui()->mainTemplate()->addCss($version_parameter->appendToUrl(self::plugin()->directory() . "/css/srtile.css"));

            $tpl = self::plugin()->template("TileCollection/collection.html");

            $tpl->setVariableEscaped("VIEW", $parent_tile->getView());

            $tile_html = self::output()->getHTML(array_map(function (Tile $tile) : SingleGUIInterface {
                self::dic()->appEventHandler()->raise(IL_COMP_PLUGIN . "/" . ilSrTilePlugin::PLUGIN_NAME, ilSrTilePlugin::EVENT_CHANGE_TILE_BEFORE_RENDER, [
                    "tile" => $tile
                ]);

                return self::srTile()->tiles()->renderer()->factory()->newSingleGUIInstance($this, $tile);
            }, $this->collection->getTiles()));

            $tpl->setVariable("TILES", $tile_html);

            if (!self::dic()->ctrl()->isAsynch() && $parent_tile->getShowLearningProgressFilter() === Tile::SHOW_TRUE) {
                LearningProgressFilterGUI::initToolbar(intval(ilSrTileUIHookGUI::filterRefId()));
            }

            if (!self::dic()->ctrl()->isAsynch() && $parent_tile->getShowLearningProgressLegend() === Tile::SHOW_TRUE) {
                $tpl->setVariable("LP_LEGEND", self::output()->getHTML(new LearningProgressLegendGUI()));
            }

            $collection_html = self::output()->getHTML($tpl);

            $this->hideOriginalRowsOfTiles();
        }

        return $collection_html;
    }


    /**
     *
     */
    protected function initJS()/*: void*/
    {
        $version_parameter = PluginVersionParameter::getInstance()->withPlugin(self::plugin());

        self::dic()->ui()->mainTemplate()->addJavaScript($version_parameter->appendToUrl(self::plugin()->directory() . "/node_modules/@iconfu/svg-inject/dist/svg-inject.min.js"));
    }
}
