<?php

namespace srag\Plugins\SrTile\Tile\Renderer\Container;

use Closure;
use ilFileUploadGUI;
use ilFileUploadUtil;
use srag\Plugins\SrTile\Tile\Renderer\AbstractSingleGUI;

/**
 * Class ContainerSingleGUI
 *
 * @package srag\Plugins\SrTile\Tile\Renderer\Container
 */
class ContainerSingleGUI extends AbstractSingleGUI
{

    protected static $reset_file_upload_gui = false;


    /**
     * @return string
     */
    public function render() : string
    {
        $html = parent::render();

        if (!self::$reset_file_upload_gui) {
            // The generated HTML/OnLoadCode of ilFileUploadGUI is incompatible with this Plugin - try to reset it once ...

            // Main HTML was removed through ilSrTileUIHookGUI - Force it again
            Closure::bind(function () {
                ilFileUploadGUI::$shared_code_loaded = false;
            }, null, ilFileUploadGUI::class)();

            // Remove on load code which not works anymore because refers to not exists element
            foreach ((array) self::dic()->ui()->mainTemplate()->on_load_code as &$codes) {
                foreach ($codes as &$code) {
                    if (strpos($code, "FileUpload") !== false) {
                        $code = "";
                    }
                }
            }

            self::$reset_file_upload_gui = true;
        }

        if (ilFileUploadUtil::isUploadAllowed($this->tile->getObjRefId())) {

            $html = self::output()->getHTML([
                $html,
                new ilFileUploadGUI("sr_tile_" . $this->tile->getTileId(), $this->tile->getObjRefId()) // ... and generate new ilFileUploadGUI
            ]);
        }

        return $html;
    }
}
