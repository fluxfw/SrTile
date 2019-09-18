<?php

namespace srag\Plugins\SrTile\Metadata;

use ilMD;
use ilObject;
use ilSrTilePlugin;
use srag\DIC\SrTile\DICTrait;
use srag\Plugins\SrTile\Utils\SrTileTrait;

/**
 * Class Metadata
 *
 * @package srag\Plugins\SrTile\Metadata
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class Metadata
{

    use SrTileTrait;
    use DICTrait;
    const PLUGIN_CLASS_NAME = ilSrTilePlugin::class;
    /**
     * @var self[]
     */
    protected static $instances = [];


    /**
     * @param ilObject $il_object
     *
     * @return self
     */
    public static function getInstance(ilObject $il_object) : self
    {
        if (!isset(self::$instances[$il_object->getId()])) {
            self::$instances[$il_object->getId()] = new self($il_object);
        }

        return self::$instances[$il_object->getId()];
    }


    /**
     * @var ilObject
     */
    protected $il_object;


    /**
     * Metadata constructor
     *
     * @param ilObject $il_object
     */
    private function __construct(ilObject $il_object)
    {
        $this->il_object = $il_object;
    }


    /**
     * @return string
     */
    public function getLanguageFlagImagePath() : string
    {
        $language_code = $this->getLanguageCode();

        if (file_exists($image_path = self::plugin()->directory() . "/templates/images/Language/" . $language_code . ".png")) {
            return $image_path;
        }

        return "";
    }


    /**
     * @return string
     */
    private function getLanguageCode() : string
    {
        $il_md = new ilMD($this->il_object->getId(), $this->il_object->getId(), $this->il_object->getType());

        /**
         * var $md_language ilMDLanguage
         */
        $md_language = $il_md->getGeneral()->getLanguage(current($il_md->getGeneral()->getLanguageIds()));

        return ($md_language->getLanguageCode() !== false ? $md_language->getLanguageCode() : "");
    }
}
