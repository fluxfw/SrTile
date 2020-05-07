<?php

namespace srag\Plugins\SrTile\Recommend;

use ilPropertyFormGUI;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Tile\Tile;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class RecommendGUI
 *
 * @package           srag\Plugins\SrTile\Recommend
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy srag\Plugins\SrTile\Recommend\RecommendGUI: ilUIPluginRouterGUI
 */
class RecommendGUI
{

    use DICTrait;
    use SrTileTrait;

    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    const CMD_ADD_RECOMMEND = "addRecommend";
    const CMD_NEW_RECOMMEND = "newRecommend";
    const GET_PARAM_REF_ID = "ref_id";
    const LANG_MODULE = "recommendation";
    /**
     * @var Recommend
     */
    protected $recommend;


    /**
     * RecommendGUI constructor
     */
    public function __construct()
    {

    }


    /**
     *
     */
    public function executeCommand()/*: void*/
    {
        $this->recommend = self::srTile()->recommends()->factory()->newInstance(self::srTile()->tiles()->getInstanceForObjRefId(intval(filter_input(INPUT_GET, self::GET_PARAM_REF_ID))));

        if (!($this->recommend->getTile()->getShowRecommendIcon() === Tile::SHOW_TRUE
            && !empty($this->recommend->getTile()->getRecommendMailTemplate())
            && self::srTile()->access()->hasReadAccess($this->recommend->getTile()->getObjRefId()))
        ) {
            die();
        }

        self::dic()->ctrl()->saveParameter($this, self::GET_PARAM_REF_ID);

        $this->setTabs();

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch ($next_class) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_ADD_RECOMMEND:
                    case self::CMD_NEW_RECOMMEND:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     *
     */
    protected function setTabs()/*:void*/
    {

    }


    /**
     * @return string
     */
    public function getModal() : string
    {
        self::dic()->ui()->mainTemplate()->addJavaScript(self::plugin()->directory() . "/js/recommend.min.js");

        $modal = self::output()->getHTML(self::dic()->ui()->factory()->modal()->roundtrip("", self::dic()->ui()->factory()->legacy("")));

        // SrTile needs so patches on the new roundtrip modal ui

        // tile_recommend_modal
        $modal = str_replace('<div class="modal ', '<div class="tile_recommend_modal modal ', $modal);

        // Large modal
        $modal = str_replace('<div class="modal-dialog"', '<div class="modal-dialog modal-lg"', $modal);

        // Buttons will delivered over the form gui
        $modal = str_replace('<div class="modal-footer">', '<div class="modal-footer" style="display:none;">', $modal);

        return $modal;
    }


    /**
     * @param string|null       $message
     * @param ilPropertyFormGUI $form
     */
    protected function show(/*?string*/
        $message,
        ilPropertyFormGUI $form
    )/*: void*/
    {
        $tpl = self::plugin()->template("Recommend/recommend_modal.html");

        if ($message !== null) {
            $tpl->setCurrentBlock("recommend_message");
            $tpl->setVariable("MESSAGE", $message);
        }

        $tpl->setCurrentBlock("recommend_form");
        $tpl->setVariable("FORM", self::output()->getHTML($form));

        self::output()->output($tpl, true);
    }


    /**
     *
     */
    protected function addRecommend()/*: void*/
    {
        $message = null;

        $form = self::srTile()->recommends()->factory()->newFormInstance($this, $this->recommend);

        $this->show($message, $form);
    }


    /**
     *
     */
    protected function newRecommend()/*: void*/
    {
        $message = null;

        $form = self::srTile()->recommends()->factory()->newFormInstance($this, $this->recommend);

        if (!$form->storeForm()) {
            $this->show($message, $form);

            return;
        }

        if ($this->recommend->send()) {
            if (self::version()->is54()) {
                $message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->success(self::plugin()
                    ->translate("sent_success", self::LANG_MODULE)));
            } else {
                $message = self::dic()->ui()->mainTemplate()->getMessageHTML(self::plugin()
                    ->translate("sent_success", self::LANG_MODULE), "success");
            }
        } else {
            if (self::version()->is54()) {
                $message = self::output()->getHTML(self::dic()->ui()->factory()->messageBox()->failure(self::plugin()
                    ->translate("sent_failure", self::LANG_MODULE)));
            } else {
                $message = self::dic()->ui()->mainTemplate()->getMessageHTML(self::plugin()
                    ->translate("sent_failure", self::LANG_MODULE), "failure");
            }
        }

        $this->show($message, $form);
    }
}
